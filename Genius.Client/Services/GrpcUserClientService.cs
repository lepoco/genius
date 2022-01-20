using Genius.Client.Interfaces;
using GeniusProtocol;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Services
{
    public class GrpcUserClientService
    {
        private readonly ILogger<GrpcUserClientService> _logger;

        private readonly User.UserClient _grpcClient;

        public GrpcUserClientService(ILogger<GrpcUserClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new User.UserClient(channel.GetChannel());
        }
    }
}
