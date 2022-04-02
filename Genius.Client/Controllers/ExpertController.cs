// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Genius.Client.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class ExpertController : ControllerBase
    {
        private readonly ILogger<ExpertController> _logger;

        private readonly Expert.ExpertClient _grpcClient;

        public ExpertController(ILogger<ExpertController> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new Expert.ExpertClient(channel.GetChannel());
        }

        [HttpGet]
        public IActionResult Get()
        {
            return StatusCode(404, "The service does not support direct reading.");
        }

        [HttpGet]
        [Route("system")]
        public async Task<IEnumerable<ExpertModel>> GetAllSystems()
        {
            var expertSystems = new List<ExpertModel>();

            using var call = _grpcClient.GetAll(new ExpertEmptyModel());

            while (await call.ResponseStream.MoveNext())
                expertSystems.Add(call.ResponseStream.Current);

            return expertSystems;
        }

        [HttpPost]
        [Route("system")]
        public async Task<IActionResult> InsertSystem()
        {
            var newSystem = new ExpertModel
            {
                Name = HttpContext.Request.Form["name"],
                Description = HttpContext.Request.Form["description"],
                Question = HttpContext.Request.Form["question"]
            };

            if (String.IsNullOrEmpty(newSystem.Name) || String.IsNullOrEmpty(newSystem.Question))
                return StatusCode(200, false);

            var newSystemId = (await _grpcClient.CreateAsync(newSystem))?.Id ?? 0;

            return StatusCode(200, newSystemId > 0 ? "success" : "error");
        }

        [HttpGet]
        [Route("system/guid/{guid}")]
        public async Task<ExpertModel> GetSingleSystemByGuid([FromRoute] string guid)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Guid = guid });
        }

        [HttpGet]
        [Route("system/{id}")]
        public async Task<ExpertModel> GetSingleSystemById([FromRoute] int id)
        {
            return await _grpcClient.GetAsync(new ExpertLookupModel { Id = id });
        }

        [HttpGet]
        [Route("system/{id}/about")]
        public async Task<ExpertAboutModel> GetSystemAbout([FromRoute] int id)
        {
            return await _grpcClient.GetAboutAsync(new ExpertLookupModel { Id = id });
        }

        [HttpDelete]
        [Route("system/{id}")]
        public async Task<IActionResult> DeleteSingleSystem([FromRoute] int id)
        {
            if (id < 1)
                return StatusCode(400, "Unable to delete unknown system");

            var system = await _grpcClient.DeleteAsync(new ExpertLookupModel { Id = id });
            var isDeleted = system != null && system.Id > 0;

            return StatusCode(200, isDeleted ? "success" : "error");
        }

        [HttpGet]
        [Route("system/{systemId}/conditions")]
        public async Task<IEnumerable<ConditionModel>> GetAllConditions([FromRoute] int systemId)
        {
            var systemConditions = new List<ConditionModel>();

            using var call = _grpcClient.GetSystemConditions(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemConditions.Add(call.ResponseStream.Current);

            return systemConditions;
        }

        [HttpGet]
        [Route("system/{systemId}/products")]
        public async Task<IEnumerable<ProductModel>> GetAllProducts([FromRoute] int systemId)
        {
            var systemProducts = new List<ProductModel>();

            using var call = _grpcClient.GetSystemProducts(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemProducts.Add(call.ResponseStream.Current);

            return systemProducts;
        }

        [HttpGet]
        [Route("system/{systemId}/relations")]
        public async Task<IEnumerable<RelationModel>> GetAllRelations([FromRoute] int systemId)
        {
            var systemRelations = new List<RelationModel>();

            using var call = _grpcClient.GetSystemRelations(new ExpertLookupModel { Id = systemId });

            while (await call.ResponseStream.MoveNext())
                systemRelations.Add(call.ResponseStream.Current);

            return systemRelations;
        }

        [HttpPost]
        [Route("product")]
        public async Task<IActionResult> InsertProduct()
        {
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int systemId);

            if (systemId < 1)
                return StatusCode(200, 0);

            string rawConditionsList = HttpContext.Request.Form["conditions"];

            var newProduct = new ProductModel
            {
                SystemId = systemId,
                Name = HttpContext.Request.Form["name"],
                Description = HttpContext.Request.Form["description"],
                Notes = HttpContext.Request.Form["notes"]
            };

            var newProductId = (await _grpcClient.AddProductAsync(newProduct))?.Id ?? 0;

            if (newProductId > 0 && !String.IsNullOrEmpty(rawConditionsList))
                await AddProductConditions(systemId, newProductId, rawConditionsList);

            return StatusCode(200, newProductId);
        }

        [HttpPost]
        [Route("product/update")]
        public async Task<IActionResult> UpdateProduct()
        {
            //Int32.TryParse(HttpContext.Request.Form["systemId"], out int systemId);
            //Int32.TryParse(HttpContext.Request.Form["id"], out int productId);

            //if (systemId < 1 || productId < 1)
            //    return StatusCode(200, 0);

            //string rawConditionsList = HttpContext.Request.Form["conditions"];

            //var newProduct = new ProductModel
            //{
            //    SystemId = systemId,
            //    Name = HttpContext.Request.Form["name"],
            //    Description = HttpContext.Request.Form["description"],
            //    Notes = HttpContext.Request.Form["notes"]
            //};

            //var newProductId = await _expertClient.AddProductAsync(newProduct);

            //if (newProductId > 0 && !String.IsNullOrEmpty(rawConditionsList))
            //    await AddProductConditions(systemId, newProductId, rawConditionsList);

            return StatusCode(200, 0);
        }

        [HttpGet]
        [Route("product/{id}")]
        public async Task<ProductModel> GetSingleProduct([FromRoute] int id)
        {
            return await _grpcClient.GetProductAsync(new ProductLookupModel { Id = id });
        }

        [HttpGet]
        [Route("product/{id}/relations")]
        public async Task<ProductRelationsModel> GetProductRelations([FromRoute] int id)
        {
            return await _grpcClient.GetProductRelationsAsync(new ProductLookupModel { Id = id });
        }

        [HttpPost]
        [Route("condition")]
        public async Task<IActionResult> InsertCondition()
        {
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int systemId);

            if (systemId < 1)
                return StatusCode(200, 0);

            var newCondition = new ConditionModel
            {
                SystemId = systemId,
                Name = HttpContext.Request.Form["name"],
                Description = HttpContext.Request.Form["description"],
            };

            var newConditionId = (await _grpcClient.AddConditionAsync(newCondition))?.Id ?? 0;

            return StatusCode(200, newConditionId);
        }

        [HttpGet]
        [Route("condition/{id}")]
        public async Task<ConditionModel> GetSingleCondition([FromRoute] int id)
        {
            return await _grpcClient.GetConditionAsync(new ConditionLookupModel { Id = id });
        }

        [HttpGet]
        [Route("condition/{id}/relations")]
        public async Task<ConditionRelationsModel> GetConditionRelations([FromRoute] int id)
        {
            return await _grpcClient.GetConditionRelationsAsync(new ConditionLookupModel { Id = id });
        }

        [HttpPost]
        [Route("relation")]
        public async Task<IActionResult> InsertRelation()
        {
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int systemId);
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int conditionId);
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int productId);
            Int32.TryParse(HttpContext.Request.Form["systemId"], out int weight);

            if (systemId < 1 || conditionId < 1 || productId < 1)
                return StatusCode(200, 0);

            if (weight > 100)
                weight = 100;

            if (weight < 0)
                weight = 0;

            var newRelation = new RelationModel
            {
                SystemId = systemId,
                ConditionId = conditionId,
                ProductId = productId,
                Weight = weight
            };

            var serverResponse = (await _grpcClient.AddRelationAsync(newRelation))?.Id ?? 0;

            return StatusCode(200, serverResponse);
        }

        [HttpGet]
        [Route("relation/{id}")]
        public async Task<RelationModel> GetSingleRelation([FromRoute] int id)
        {
            return await _grpcClient.GetRelationAsync(new RelationLookupModel { Id = id });
        }

        private async Task<IEnumerable<int>> AddProductConditions(int systemId, int productId, string rawConditions)
        {
            var conditionsList = rawConditions.Trim().Split(',');
            var currentCondition = 0;

            List<int> relationIds = new List<int>();

            foreach (var rawCondition in conditionsList)
            {
                Int32.TryParse(rawCondition, out currentCondition);

                if (currentCondition < 1)
                    continue;

                var dbCondition = await _grpcClient.GetConditionAsync(new ConditionLookupModel { Id = currentCondition });

                if (dbCondition?.Id > 0 || dbCondition?.SystemId == systemId)
                {
                    var newRelation = (await _grpcClient.AddRelationAsync(new RelationModel
                    {
                        SystemId = systemId,
                        ProductId = productId,
                        ConditionId = dbCondition.Id,
                        Weight = 100
                    }))?.Id ?? 0;

                    if (newRelation > 0)
                    {
                        relationIds.Add(newRelation);
                    }
                }
            }

            return relationIds;
        }
    }
}