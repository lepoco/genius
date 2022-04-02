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
        private readonly ILogger<GrpcChannelService> _logger;

        private readonly GrpcChannel _channel;

        private readonly Dictionary<int, object> _clients = new();

        public GrpcChannelService(ILogger<GrpcChannelService> logger)
        {
            _logger = logger;

            _channel = GrpcChannel.ForAddress("https://localhost:5006", new GrpcChannelOptions
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
        public T GetClient<T>() where T : Grpc.Core.ClientBase
        {
            var hash = typeof(T).GetHashCode();

            if (!_clients.ContainsKey(hash))
                _clients.Add(hash, (T)Activator.CreateInstance(typeof(T), _channel));

            return (T)_clients[hash];
        }

        /// <inheritdoc />
        public GrpcChannel GetChannel()
        {
            return _channel;
        }
    }
}
