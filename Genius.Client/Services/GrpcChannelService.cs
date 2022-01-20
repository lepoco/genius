using Genius.Client.Interfaces;
using Grpc.Net.Client;
using Microsoft.Extensions.Logging;
using System;
using System.Net.Http;
using System.Threading;

namespace Genius.Client.Services
{
    public class GrpcChannelService : IChannel
    {
        private readonly ILogger<GrpcChannelService> _logger;

        private readonly GrpcChannel _channel;

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

        public GrpcChannel GetChannel()
        {
            return _channel;
        }
    }
}
