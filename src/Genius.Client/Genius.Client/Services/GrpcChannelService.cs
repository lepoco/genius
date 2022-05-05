// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Interfaces;
using Grpc.Net.Client;
using Microsoft.Extensions.Logging;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading;

namespace Genius.Client.Services
{
    /// <summary>
    /// Implements a universal connection with the Genius microservice available to other gRPC services.
    /// <para>Thanks to this, allows to change the connection with the Genius service without changing the parameters of other internal services.</para>
    /// </summary>
    public class GrpcChannelService : IChannel
    {
        /// <inheritdoc />
        public string ChannelAddress { get; set; } = "https://localhost:5006";

        private readonly ILogger<GrpcChannelService> _logger;

        private readonly GrpcChannel _channel;

        private readonly Dictionary<Type, object> _clients = new();

        public GrpcChannelService(ILogger<GrpcChannelService> logger)
        {
            _logger = logger;

            _channel = GrpcChannel.ForAddress(ChannelAddress, new GrpcChannelOptions
            {
                HttpHandler = new SocketsHttpHandler
                {
                    PooledConnectionIdleTimeout = Timeout.InfiniteTimeSpan,
                    KeepAlivePingDelay = TimeSpan.FromSeconds(60),
                    KeepAlivePingTimeout = TimeSpan.FromSeconds(30),
                    EnableMultipleHttp2Connections = true
                }
            });
        }

        /// <inheritdoc />
        public GrpcChannel GetChannel() => _channel;

        /// <inheritdoc />
        public T GetClient<T>() where T : Grpc.Core.ClientBase
        {
            var type = typeof(T);

            _clients.TryGetValue(type, out var clientObject);

            if (clientObject != null)
                return (T)clientObject;

            clientObject = Activator.CreateInstance(type, _channel);

            _clients.Add(type, clientObject);

            return (T)clientObject;
        }
    }
}
