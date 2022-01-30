using Genius.Client.Interfaces;
using GeniusProtocol;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Services
{
    public class GrpcStatisticsClientService
    {
        private readonly ILogger<GrpcStatisticsClientService> _logger;

        private readonly Statistic.StatisticClient _grpcClient;

        public GrpcStatisticsClientService(ILogger<GrpcStatisticsClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new Statistic.StatisticClient(channel.GetChannel());

            // TODO: To be implemented
        }
    }
}
