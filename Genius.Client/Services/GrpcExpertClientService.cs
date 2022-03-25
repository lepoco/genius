// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Genius.Client.Services
{
    /// <summary>
    /// Responsible for establishing the gRPC connection with the Genius microservice.
    /// <para>Separates the gRPC query logic from internal operations.</para>
    /// </summary>
    public class GrpcExpertClientService
    {
        private readonly ILogger<GrpcExpertClientService> _logger;

        private readonly Expert.ExpertClient _grpcClient;

        public GrpcExpertClientService(ILogger<GrpcExpertClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new Expert.ExpertClient(channel.GetChannel());
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetAsync"/>.
        /// </summary>
        public async Task<ExpertModel> GetSystemAsync(int id)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Id = id });
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetAsync"/>.
        /// </summary>
        public async Task<ExpertModel> GetSystemByGuidAsync(string guid)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Guid = guid });
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetAll"/>.
        /// </summary>
        public async Task<IEnumerable<ExpertModel>> GetAllSystems()
        {
            var expertSystems = new List<ExpertModel>();

            using var call = _grpcClient.GetAll(new ExpertEmptyModel());

            while (await call.ResponseStream.MoveNext())
                expertSystems.Add(call.ResponseStream.Current);

            return expertSystems;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.CreateAsync"/>.
        /// </summary>
        public async Task<int> CreateSystemAsync(ExpertModel system)
        {
            return (await _grpcClient.CreateAsync(system))?.Id ?? 0;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.DeleteAsync"/>.
        /// </summary>
        public async Task<int> DeleteSystemAsync(ExpertModel system)
        {
            return (await _grpcClient.DeleteAsync(new ExpertLookupModel { Id = system.Id })).Id;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetSystemConditions"/>.
        /// </summary>
        public async Task<IEnumerable<ConditionModel>> GetSystemConditionsAsync(int systemId)
        {
            var systemConditions = new List<ConditionModel>();

            using var call = _grpcClient.GetSystemConditions(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemConditions.Add(call.ResponseStream.Current);

            return systemConditions;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetSystemProducts"/>.
        /// </summary>
        public async Task<IEnumerable<ProductModel>> GetSystemProductsAsync(int systemId)
        {
            var systemProducts = new List<ProductModel>();

            using var call = _grpcClient.GetSystemProducts(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemProducts.Add(call.ResponseStream.Current);

            return systemProducts;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetSystemRelations"/>.
        /// </summary>
        public async Task<IEnumerable<RelationModel>> GetSystemRelationsAsync(int systemId)
        {
            var systemRelations = new List<RelationModel>();

            using var call = _grpcClient.GetSystemRelations(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemRelations.Add(call.ResponseStream.Current);

            return systemRelations;
        }

        public async Task<ProductRelationsModel> GetProductRelations(int productId)
        {
            return await _grpcClient.GetProductRelationsAsync(new ProductLookupModel { Id = productId });
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.AddConditionAsync"/>.
        /// </summary>
        public async Task<int> AddConditionAsync(ConditionModel condition)
        {
            return (await _grpcClient.AddConditionAsync(condition))?.Id ?? 0;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.AddProductAsync"/>.
        /// </summary>
        public async Task<int> AddProductAsync(ProductModel product)
        {
            return (await _grpcClient.AddProductAsync(product))?.Id ?? 0;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.AddRelationAsync"/>.
        /// </summary>
        public async Task<int> AddRelationAsync(RelationModel relation)
        {
            return (await _grpcClient.AddRelationAsync(relation))?.Id ?? 0;
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetProductAsync"/>.
        /// </summary>
        public async Task<ProductModel> GetProductAsync(int productId, int systemId = 0)
        {
            return await _grpcClient.GetProductAsync(new ProductLookupModel { Id = productId, SystemId = systemId });
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetConditionAsync"/>.
        /// </summary>
        public async Task<ConditionModel> GetConditionAsync(int conditionId, int systemId = 0)
        {
            return await _grpcClient.GetConditionAsync(new ConditionLookupModel { Id = conditionId, SystemId = systemId });
        }

        /// <summary>
        /// Wraps <see cref="Expert.ExpertClient.GetRelationAsync"/>.
        /// </summary>
        public async Task<RelationModel> GetRelationAsync(int relationId, int systemId = 0)
        {
            return await _grpcClient.GetRelationAsync(new RelationLookupModel { Id = relationId, SystemId = systemId });
        }
    }
}