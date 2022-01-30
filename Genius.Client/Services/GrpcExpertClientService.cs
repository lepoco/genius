using Genius.Client.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Genius.Client.Services
{
    public class GrpcExpertClientService
    {
        private readonly ILogger<GrpcExpertClientService> _logger;

        private readonly Expert.ExpertClient _grpcClient;

        public GrpcExpertClientService(ILogger<GrpcExpertClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new Expert.ExpertClient(channel.GetChannel());
        }

        public ExpertModel GetSystem(int id)
        {
            return _grpcClient.Get(new ExpertLookupModel { Id = id });
        }

        public async Task<ExpertModel> GetSystemAsync(int id)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Id = id });
        }

        public ExpertModel GetSystemByGuid(string guid)
        {
            return _grpcClient.Get(new ExpertLookupModel { Guid = guid });
        }

        public async Task<ExpertModel> GetSystemByGuidAsync(string guid)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Guid = guid });
        }

        public async Task<IEnumerable<ExpertModel>> GetAllSystems()
        {
            var expertSystems = new List<ExpertModel>();

            using var call = _grpcClient.GetAll(new ExpertEmptyModel());

            while (await call.ResponseStream.MoveNext())
            {
                expertSystems.Add(call.ResponseStream.Current);
            }

            return expertSystems;
        }

        public int CreateSystem(ExpertModel system)
        {
            return _grpcClient.Create(system)?.Id ?? 0;
        }

        public async Task<int> CreateSystemAsync(ExpertModel system)
        {
            return (await _grpcClient.CreateAsync(system))?.Id ?? 0;
        }

        public int DeleteSystem(ExpertModel system)
        {
            return _grpcClient.Delete(new ExpertLookupModel { Id = system.Id }).Id;
        }

        public async Task<int> DeleteSystemAsync(ExpertModel system)
        {
            return (await _grpcClient.DeleteAsync(new ExpertLookupModel { Id = system.Id })).Id;
        }
    }
}
