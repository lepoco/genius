// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading;
using Genius.Client.Interfaces;
using Genius.Client.Settings;
using Grpc.Net.Client;
using Microsoft.Extensions.Logging;
using Microsoft.Extensions.Options;

namespace Genius.Client.Services;

/// <summary>
/// Implements a universal connection with the Genius microservice available to other gRPC services.
/// <para>Thanks to this, allows to change the connection with the Genius service without changing the parameters of other internal services.</para>
/// </summary>
public class GrpcChannelService : IChannel
{
    /// <inheritdoc />
    public string ChannelAddress { get; set; } = "https://localhost:5006";

    private readonly ILogger<GrpcChannelService> _logger;

    private readonly IOptions<ServicesSettings> _services;

    private readonly GrpcChannel _expertGrpcChannel;

    private readonly GrpcChannel _authorizationGrpcChannel;

    private readonly GrpcChannel _statisticsGrpcChannel;

    private readonly Dictionary<Type, object> _clients = new();

    public GrpcChannelService(ILogger<GrpcChannelService> logger, IOptions<ServicesSettings> servicesSettings)
    {
        _logger = logger;
        _services = servicesSettings;

        _expertGrpcChannel = GrpcChannel.ForAddress(servicesSettings.Value.Genius, new GrpcChannelOptions
        {
            HttpHandler = new SocketsHttpHandler
            {
                PooledConnectionIdleTimeout = Timeout.InfiniteTimeSpan,
                KeepAlivePingDelay = TimeSpan.FromSeconds(60),
                KeepAlivePingTimeout = TimeSpan.FromSeconds(30),
                EnableMultipleHttp2Connections = true
            }
        });

        _authorizationGrpcChannel = GrpcChannel.ForAddress(servicesSettings.Value.OAuth, new GrpcChannelOptions
        {
            HttpHandler = new SocketsHttpHandler
            {
                PooledConnectionIdleTimeout = Timeout.InfiniteTimeSpan,
                KeepAlivePingDelay = TimeSpan.FromSeconds(60),
                KeepAlivePingTimeout = TimeSpan.FromSeconds(30),
                EnableMultipleHttp2Connections = true
            }
        });

        _statisticsGrpcChannel = GrpcChannel.ForAddress(servicesSettings.Value.Statistics, new GrpcChannelOptions
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
    public GrpcChannel GetChannel() => _expertGrpcChannel;

    /// <inheritdoc />
    public T GetClient<T>() where T : Grpc.Core.ClientBase
    {
        var type = typeof(T);

        _clients.TryGetValue(type, out var clientObject);

        if (clientObject != null)
            return (T)clientObject;

        clientObject = Activator.CreateInstance(type, _expertGrpcChannel);

        _clients.Add(type, clientObject);

        return (T)clientObject;
    }
}
