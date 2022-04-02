// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using System;
using System.Linq;
using System.Threading.Tasks;
using RelationType = Genius.Data.Models.Expert.RelationType;

namespace Genius.Services
{
    public class GrpcExpertService : GeniusProtocol.Expert.ExpertBase
    {
        private readonly ILogger<GrpcExpertService> _logger;

        private readonly IExpertService _expertService;

        public GrpcExpertService(ILogger<GrpcExpertService> logger, IExpertService expertService)
        {
            _logger = logger;
            _expertService = expertService;
        }

        #region SYSTEM OPERATIONS

        public override async Task<ExpertResponseModel> Create(ExpertModel request, ServerCallContext context)
        {
            _logger.LogInformation($"{nameof(Create)}, with new request: {request?.Name}, {request?.Question}");

            if (String.IsNullOrEmpty(request?.Name) || String.IsNullOrEmpty(request?.Question))
                return new ExpertResponseModel { Id = 0 };

            var existingSystems =
                await _expertService.Context.Systems.Where(sys => sys.Name == request.Name).ToListAsync();

            // System already exist
            if (existingSystems.Count > 0) return new ExpertResponseModel { Id = 0 };

            var insertedSystem = new Data.Models.Expert.System
            {
                Name = request.Name,
                Description = request.Description,
                Question = request.Question ?? "",
                CreatedAt = DateTime.Now,
                UpdatedAt = DateTime.Now,
                Version = "1.0.0",
                Type = SystemType.Conditional,
                Guid = Guid.NewGuid().ToString()
            };

            _expertService.Context.Systems.Add(insertedSystem);

            await _expertService.Context.SaveChangesAsync();

            return new ExpertResponseModel { Id = insertedSystem?.Id ?? 0 };
        }

        public override async Task GetAll(ExpertEmptyModel request, IServerStreamWriter<ExpertModel> responseStream,
            ServerCallContext context)
        {
            var savedSystems = _expertService.Context.Systems;

            foreach (Data.Models.Expert.System singleSystem in savedSystems)
            {
                await responseStream.WriteAsync(new ExpertModel
                {
                    Id = singleSystem.Id,
                    Version = singleSystem.Version,
                    Guid = singleSystem.Guid,
                    Name = singleSystem.Name,
                    Description = singleSystem.Description,
                    Question = singleSystem.Question,
                    CreatedAt = singleSystem.CreatedAt.ToString(),
                    UpdatedAt = singleSystem.UpdatedAt.ToString()
                });
            }
        }

        public override async Task<ExpertModel> Get(ExpertLookupModel request, ServerCallContext context)
        {
            Data.Models.Expert.System expertSystem = new Data.Models.Expert.System { Id = 0 };

            if (!String.IsNullOrEmpty(request?.Guid))
                expertSystem =
                    await _expertService.Context.Systems.Where(sys => sys.Guid == request.Guid.Trim())
                        .FirstOrDefaultAsync() ?? new Data.Models.Expert.System { Id = 0 };

            if ((request?.Id ?? 0) > 0)
                expertSystem =
                    await _expertService.Context.Systems.Where(sys => sys.Id == request.Id).FirstOrDefaultAsync() ??
                    new Data.Models.Expert.System { Id = 0 };

            if (expertSystem.Id < 1) return new ExpertModel();

            return new ExpertModel
            {
                Id = expertSystem.Id,
                Version = expertSystem.Version,
                Guid = expertSystem.Guid,
                Name = expertSystem.Name,
                Description = expertSystem.Description,
                Question = expertSystem.Question,
                CreatedAt = expertSystem.CreatedAt.ToString(),
                UpdatedAt = expertSystem.UpdatedAt.ToString()
            };
        }

        public override async Task<ExpertAboutModel> GetAbout(ExpertLookupModel request, ServerCallContext context)
        {
            Data.Models.Expert.System expertSystem = new Data.Models.Expert.System { Id = 0 };

            if (!String.IsNullOrEmpty(request?.Guid))
                expertSystem =
                    await _expertService.Context.Systems.Where(sys => sys.Guid == request.Guid.Trim())
                        .FirstOrDefaultAsync() ?? new Data.Models.Expert.System { Id = 0 };

            if ((request?.Id ?? 0) > 0)
                expertSystem =
                    await _expertService.Context.Systems.Where(sys => sys.Id == request.Id).FirstOrDefaultAsync() ??
                    new Data.Models.Expert.System { Id = 0 };

            if (expertSystem.Id < 1) return new ExpertAboutModel();

            return new ExpertAboutModel
            {
                Id = expertSystem.Id,
                Conditions =
                    await _expertService.Context.Conditions
                        .Where(condition => condition.SystemId == expertSystem.Id)
                        .CountAsync(),
                Products =
                    await _expertService.Context.Products.Where(product => product.SystemId == expertSystem.Id)
                        .CountAsync(),
                Relations = await _expertService.Context.Relations
                    .Where(relation => relation.SystemId == expertSystem.Id)
                    .CountAsync(),
            };
        }

        public override async Task<ExpertResponseModel> Delete(ExpertLookupModel request, ServerCallContext context)
        {
            if (request?.Id < 1) return new ExpertResponseModel { Id = 0 };

            var expertSystem =
                await _expertService.Context.Systems.Where(sys => sys.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.System { Id = 0 };

            // Does not exist
            if (expertSystem.Id < 1) return new ExpertResponseModel { Id = 0 };

            int systemId = expertSystem.Id;

            _expertService.Context.Relations.RemoveRange(
                _expertService.Context.Relations.Where(relation => relation.SystemId == systemId));
            _expertService.Context.Conditions.RemoveRange(
                _expertService.Context.Conditions.Where(condition => condition.SystemId == systemId));
            _expertService.Context.Products.RemoveRange(
                _expertService.Context.Products.Where(product => product.SystemId == systemId));

            _expertService.Context.Systems.Remove(expertSystem);

            // TODO: Remove conditions, products and relations

            await _expertService.Context.SaveChangesAsync();

            return new ExpertResponseModel { Id = systemId };
        }

        #endregion

        #region ADD PARTIALS

        public override async Task<ConditionModel> AddCondition(ConditionModel request, ServerCallContext context)
        {
            if (request == null) return new ConditionModel { Id = 0 };

            if (request?.SystemId < 1) return request;

            var expertSystem =
                await _expertService.Context.Systems.Where(sys => sys.Id == request.SystemId).FirstOrDefaultAsync() ??
                new Data.Models.Expert.System { Id = 0 };

            if (expertSystem.Id < 1) return request;

            if (String.IsNullOrEmpty(request?.Name)) return request;

            var existingConditions = await _expertService.Context.Conditions
                .Where(con => con.SystemId == expertSystem.Id && con.Name == request.Name)
                .ToListAsync();

            // Already exists
            if (existingConditions.Count > 0) return request;

            var insertedCondition = new Data.Models.Expert.Condition
            {
                SystemId = expertSystem.Id,
                Name = request.Name,
                Description = request.Description ?? String.Empty,
            };

            _expertService.Context.Conditions.Add(insertedCondition);

            await _expertService.Context.SaveChangesAsync();

            request.Id = insertedCondition.Id;

            return request;
        }

        public override async Task<ProductModel> AddProduct(ProductModel request, ServerCallContext context)
        {
            if (request == null) return new ProductModel { Id = 0 };

            if (request?.SystemId < 1) return request;

            var expertSystem =
                await _expertService.Context.Systems.Where(sys => sys.Id == request.SystemId).FirstOrDefaultAsync() ??
                new Data.Models.Expert.System { Id = 0 };

            if (expertSystem.Id < 1) return request;

            if (String.IsNullOrEmpty(request?.Name)) return request;

            var existingProducts = await _expertService.Context.Products
                .Where(prod => prod.SystemId == expertSystem.Id && prod.Name == request.Name)
                .ToListAsync();

            // Already exists
            if (existingProducts.Count > 0) return request;

            var insertedProduct = new Data.Models.Expert.Product
            {
                SystemId = expertSystem.Id,
                Name = request.Name,
                Description = request.Description ?? String.Empty,
                Notes = request.Notes ?? String.Empty
            };

            _expertService.Context.Products.Add(insertedProduct);

            await _expertService.Context.SaveChangesAsync();

            request.Id = insertedProduct.Id;

            return request;
        }

        public override async Task<RelationModel> AddRelation(RelationModel request, ServerCallContext context)
        {
            if (request == null) return new RelationModel { Id = 0 };

            if (request?.SystemId < 1 || request?.ConditionId < 1 || request?.ProductId < 1) return request;

            var expertSystem =
                await _expertService.Context.Systems.Where(sys => sys.Id == request.SystemId).FirstOrDefaultAsync() ??
                new Data.Models.Expert.System { Id = 0 };

            var databaseCondition =
                await _expertService.Context.Conditions.Where(con => con.Id == request.ConditionId)
                    .FirstOrDefaultAsync() ?? new Data.Models.Expert.Condition { Id = 0 };

            var databaseProduct =
                await _expertService.Context.Products.Where(prod => prod.Id == request.ProductId)
                    .FirstOrDefaultAsync() ?? new Data.Models.Expert.Product { Id = 0 };

            if (expertSystem.Id < 1 || databaseProduct.Id < 1 || databaseCondition.Id < 1) return request;

            if (request.Weight > 100) request.Weight = 100;

            if (request.Weight < 0) request.Weight = 0;

            var insertedRelation = new Data.Models.Expert.Relation
            {
                SystemId = expertSystem.Id,
                ProductId = databaseProduct.Id,
                CondiotionId = databaseCondition.Id,
                Weight = request.Weight
            };

            _expertService.Context.Relations.Add(insertedRelation);

            await _expertService.Context.SaveChangesAsync();

            request.Id = insertedRelation.Id;

            return request;
        }

        #endregion

        #region GET PARTIALS

        public override async Task<ConditionModel> GetCondition(ConditionLookupModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return new ConditionModel { Id = 0 };

            var databaseCondition =
                await _expertService.Context.Conditions.Where(con => con.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.Condition { Id = 0, SystemId = 0 };

            return new ConditionModel
            {
                Id = databaseCondition.Id,
                SystemId = databaseCondition.SystemId,
                Name = databaseCondition.Name ?? String.Empty,
                Description = databaseCondition.Description ?? String.Empty
            };
        }

        public override async Task<ProductModel> GetProduct(ProductLookupModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return new ProductModel { Id = 0 };

            var databaseProduct =
                await _expertService.Context.Products.Where(prod => prod.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.Product { Id = 0, SystemId = 0 };

            return new ProductModel
            {
                Id = databaseProduct.Id,
                SystemId = databaseProduct.SystemId,
                Name = databaseProduct.Name ?? String.Empty,
                Description = databaseProduct.Description ?? String.Empty,
                Notes = databaseProduct.Notes ?? String.Empty
            };
        }

        public override async Task<RelationModel> GetRelation(RelationLookupModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return new RelationModel { Id = 0 };

            var databaseRelation =
                await _expertService.Context.Relations.Where(rel => rel.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.Relation { Id = 0, SystemId = 0, CondiotionId = 0, ProductId = 0 };

            return new RelationModel
            {
                Id = databaseRelation.Id,
                SystemId = databaseRelation.SystemId,
                ConditionId = databaseRelation.CondiotionId,
                ProductId = databaseRelation.ProductId
            };
        }

        #endregion

        #region GETTERS FOR SYSTEM POOLS

        public override async Task GetSystemConditions(ExpertLookupModel request,
            IServerStreamWriter<ConditionModel> responseStream, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return;

            var systemConditions = _expertService.Context.Conditions.Where(con => con.SystemId == request.Id);

            foreach (Data.Models.Expert.Condition singleCondition in systemConditions)
            {
                await responseStream.WriteAsync(new ConditionModel
                {
                    Id = singleCondition.Id,
                    SystemId = singleCondition.SystemId,
                    Name = singleCondition.Name ?? String.Empty,
                    Description = singleCondition.Description ?? String.Empty
                });
            }
        }

        public override async Task GetSystemProducts(ExpertLookupModel request,
            IServerStreamWriter<ProductModel> responseStream, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return;

            var systemProducts = _expertService.Context.Products.Where(prod => prod.SystemId == request.Id);

            foreach (Data.Models.Expert.Product singleProduct in systemProducts)
            {
                await responseStream.WriteAsync(new ProductModel
                {
                    Id = singleProduct.Id,
                    SystemId = singleProduct.SystemId,
                    Name = singleProduct.Name ?? String.Empty,
                    Description = singleProduct.Description ?? String.Empty,
                    Notes = singleProduct.Notes ?? String.Empty
                });
            }
        }

        public override async Task GetSystemRelations(ExpertLookupModel request,
            IServerStreamWriter<RelationModel> responseStream, ServerCallContext context)
        {
            if (request == null || request.Id < 1) return;

            var systemRelations = _expertService.Context.Relations.Where(rel => rel.SystemId == request.Id);

            foreach (Data.Models.Expert.Relation singleRelation in systemRelations)
            {
                await responseStream.WriteAsync(new RelationModel
                {
                    Id = singleRelation.Id,
                    SystemId = singleRelation.SystemId,
                    ConditionId = singleRelation.CondiotionId,
                    ProductId = singleRelation.ProductId,
                    Weight = singleRelation.Weight
                });
            }
        }

        #endregion

        #region Products

        public override async Task<ProductRelationsModel> GetProductRelations(ProductLookupModel request,
            ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ProductRelationsModel
                {
                    Id = 0,
                    SystemId = 0,
                    Confirming = { },
                    Indifferent = { },
                    Negating = { }
                };

            var databaseProduct =
                await _expertService.Context.Products.Where(prod => prod.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.Product { Id = 0, SystemId = 0 };

            if (databaseProduct.Id < 1 || databaseProduct.SystemId < 1)
                return new ProductRelationsModel
                {
                    Id = 0,
                    SystemId = 0,
                    Confirming = { },
                    Indifferent = { },
                    Negating = { }
                };

            var confirming = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == request.Id && rel.Type == RelationType.Compliance)
                .Select(rel => rel.Id)
                .ToArrayAsync();
            var negating = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == request.Id && rel.Type == RelationType.Contradiction)
                .Select(rel => rel.Id)
                .ToArrayAsync();
            var indifferent = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == request.Id && rel.Type == RelationType.Disregard)
                .Select(rel => rel.Id)
                .ToArrayAsync();

            return new ProductRelationsModel
            {
                Id = databaseProduct.Id,
                SystemId = databaseProduct.SystemId,
                Confirming = { confirming },
                Indifferent = { negating },
                Negating = { indifferent },
            };
        }

        #endregion

        #region Conditions

        public override async Task<ConditionRelationsModel> GetConditionRelations(ConditionLookupModel request,
            ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ConditionRelationsModel
                {
                    Id = 0,
                    SystemId = 0,
                    Confirming = { },
                    Indifferent = { },
                    Negating = { }
                };

            var databaseCondition =
                await _expertService.Context.Conditions.Where(con => con.Id == request.Id).FirstOrDefaultAsync() ??
                new Data.Models.Expert.Condition { Id = 0, SystemId = 0 };

            if (databaseCondition.Id < 1 || databaseCondition.SystemId < 1)
                return new ConditionRelationsModel
                {
                    Id = 0,
                    SystemId = 0,
                    Confirming = { },
                    Indifferent = { },
                    Negating = { }
                };

            var confirming = await _expertService.Context.Relations
                .Where(rel => rel.CondiotionId == request.Id && rel.Type == RelationType.Compliance)
                .Select(rel => rel.Id)
                .ToArrayAsync();
            var negating = await _expertService.Context.Relations
                .Where(rel => rel.CondiotionId == request.Id && rel.Type == RelationType.Contradiction)
                .Select(rel => rel.Id)
                .ToArrayAsync();
            var indifferent = await _expertService.Context.Relations
                .Where(rel => rel.CondiotionId == request.Id && rel.Type == RelationType.Disregard)
                .Select(rel => rel.Id)
                .ToArrayAsync();

            return new ConditionRelationsModel
            {
                Id = databaseCondition.Id,
                SystemId = databaseCondition.SystemId,
                Confirming = { confirming },
                Indifferent = { negating },
                Negating = { indifferent },
            };
        }

        #endregion

        #region UPDATES

        public override async Task<ExpertResponseModel> Update(ExpertModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ExpertResponseModel { Id = 0 };

            var expertSystem = await _expertService.Context.Systems.Where(sys => sys.Id == request.Id)
                .FirstOrDefaultAsync<Data.Models.Expert.System>();

            if (expertSystem == null || expertSystem.Id < 1)
                return new ExpertResponseModel { Id = 0 };

            expertSystem.Name = request.Name;
            expertSystem.Description = request.Description;
            expertSystem.Question = request.Question;
            expertSystem.UpdatedAt = DateTime.Now;

            _expertService.Context.Systems.Update(expertSystem);
            await _expertService.Context.SaveChangesAsync();

            return new ExpertResponseModel { Id = expertSystem.Id };
        }

        public override async Task<ProductModel> UpdateProduct(ProductModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ProductModel { Id = 0, SystemId = 0 };

            var product = await _expertService.Context.Products.Where(prod => prod.Id == request.Id)
                .FirstOrDefaultAsync<Data.Models.Expert.Product>();

            if (product == null || product.Id < 1)
                return new ProductModel { Id = 0, SystemId = 0 };

            product.Name = request.Name;
            product.Description = request.Description;
            product.Notes = request.Notes;

            _expertService.Context.Products.Update(product);
            await _expertService.Context.SaveChangesAsync();

            return new ProductModel { Id = 0, SystemId = 0 };
        }

        public override async Task<ProductRelationsModel> UpdateProductConditions(ProductConditionsModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ProductRelationsModel { Id = 0, SystemId = 0 };

            var product = await _expertService.Context.Products.Where(prod => prod.Id == request.Id)
                .FirstOrDefaultAsync<Data.Models.Expert.Product>();

            if (product == null || product.Id < 1)
                return new ProductRelationsModel { Id = 0, SystemId = 0 };

            _expertService.Context.Relations.RemoveRange(
                _expertService.Context.Relations.Where(relation => relation.ProductId == product.Id));

            foreach (var singleConditionId in request.Confirming)
            {
                _expertService.Context.Relations.Add(new Data.Models.Expert.Relation
                {
                    SystemId = request.SystemId,
                    ProductId = product.Id,
                    CondiotionId = singleConditionId,
                    Weight = 100,
                    Type = RelationType.Compliance
                });
            }

            foreach (var singleConditionId in request.Negating)
            {
                _expertService.Context.Relations.Add(new Data.Models.Expert.Relation
                {
                    SystemId = product.SystemId,
                    ProductId = product.Id,
                    CondiotionId = singleConditionId,
                    Weight = 100,
                    Type = RelationType.Contradiction
                });
            }

            foreach (var singleConditionId in request.Negating)
            {
                _expertService.Context.Relations.Add(new Data.Models.Expert.Relation
                {
                    SystemId = product.SystemId,
                    ProductId = product.Id,
                    CondiotionId = singleConditionId,
                    Weight = 100,
                    Type = RelationType.Disregard
                });
            }

            await _expertService.Context.SaveChangesAsync();

            var confirmingRelations = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == product.Id && rel.Type == RelationType.Compliance)
                .Select(rel => rel.Id)
                .ToArrayAsync();

            var negatingRelations = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == product.Id && rel.Type == RelationType.Contradiction)
                .Select(rel => rel.Id)
                .ToArrayAsync();

            var indifferentRelations = await _expertService.Context.Relations
                .Where(rel => rel.ProductId == product.Id && rel.Type == RelationType.Disregard)
                .Select(rel => rel.Id)
                .ToArrayAsync();

            return new ProductRelationsModel { Id = product.Id, SystemId = 0, Confirming = { confirmingRelations }, Negating = { negatingRelations }, Indifferent = { indifferentRelations } };
        }

        public override async Task<ConditionModel> UpdateCondition(ConditionModel request, ServerCallContext context)
        {
            if (request == null || request.Id < 1)
                return new ConditionModel { Id = 0, SystemId = 0 };

            var condition = await _expertService.Context.Conditions.Where(con => con.Id == request.Id)
                .FirstOrDefaultAsync<Data.Models.Expert.Condition>();

            if (condition == null || condition.Id < 1)
                return new ConditionModel { Id = 0, SystemId = 0 };

            condition.Name = request.Name;
            condition.Description = request.Description;

            _expertService.Context.Conditions.Update(condition);
            await _expertService.Context.SaveChangesAsync();

            return new ConditionModel { Id = 0, SystemId = 0 };
        }

        public override async Task<RelationModel> UpdateRelation(RelationModel request, ServerCallContext context)
        {
            // TODO: Update relation, but with what rules?
            return new RelationModel { Id = 0, SystemId = 0 };
        }

        #endregion
    }
}