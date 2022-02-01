// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Services;
using GeniusProtocol;
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

        private readonly GrpcExpertClientService _expertClient;

        public ExpertController(ILogger<ExpertController> logger, GrpcExpertClientService expertClient)
        {
            _logger = logger;
            _expertClient = expertClient;
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
            return await _expertClient.GetAllSystems();
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

            if (String.IsNullOrEmpty(newSystem.Name) || String.IsNullOrEmpty(newSystem.Description))
                return StatusCode(200, false);

            var newSystemId = await _expertClient.CreateSystemAsync(newSystem);


            return StatusCode(200, newSystemId > 0 ? "success" : "error");
        }

        [HttpGet]
        [Route("system/{guid}")]
        public async Task<ExpertModel> GetSingleSystem([FromRoute] string guid)
        {
            return await _expertClient.GetSystemByGuidAsync(guid);
        }

        [HttpDelete]
        [Route("system/{id}")]
        public async Task<IActionResult> DeleteSingleSystem([FromRoute] int id)
        {
            bool isDeleted = await _expertClient.DeleteSystemAsync(new ExpertModel { Id = id }) > 0;

            return StatusCode(200, isDeleted ? "success" : "error");
        }

        [HttpGet]
        [Route("system/{systemId}/conditions")]
        public async Task<IEnumerable<ConditionModel>> GetAllConditions([FromRoute] int systemId)
        {
            IEnumerable<ConditionModel> conditions = await _expertClient.GetSystemConditionsAsync(systemId);

            return conditions;
        }

        [HttpGet]
        [Route("system/{systemId}/products")]
        public async Task<IEnumerable<ProductModel>> GetAllProducts([FromRoute] int systemId)
        {
            IEnumerable<ProductModel> products = await _expertClient.GetSystemProductsAsync(systemId);

            return products;
        }

        [HttpGet]
        [Route("system/{systemId}/relations")]
        public async Task<IEnumerable<RelationModel>> GetAllRelations([FromRoute] int systemId)
        {
            IEnumerable<RelationModel> relations = await _expertClient.GetSystemRelationsAsync(systemId);

            return relations;
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

            var newProductId = await _expertClient.AddProductAsync(newProduct);

            if (newProductId > 0 && !String.IsNullOrEmpty(rawConditionsList))
                await AddProductRelations(systemId, newProductId, rawConditionsList);

            return StatusCode(200, newProductId);
        }

        [HttpGet]
        [Route("product/{id}")]
        public async Task<ProductModel> GetSingleProduct([FromRoute] int id)
        {
            return await _expertClient.GetProductAsync(id);
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

            var newConditionId = await _expertClient.AddConditionAsync(newCondition);

            return StatusCode(200, newConditionId);
        }

        [HttpGet]
        [Route("condition/{id}")]
        public async Task<ConditionModel> GetSingleCondition([FromRoute] int id)
        {
            return await _expertClient.GetConditionAsync(id);
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

            var serverResponse = await _expertClient.AddRelationAsync(newRelation);

            return StatusCode(200, serverResponse);
        }

        [HttpGet]
        [Route("relation/{id}")]
        public async Task<RelationModel> GetSingleRelation([FromRoute] int id)
        {
            return await _expertClient.GetRelationAsync(id);
        }

        private async Task<IEnumerable<int>> AddProductRelations(int systemId, int productId, string rawRelations)
        {
            string[] conditionsList = rawRelations.Trim().Split(',');
            int currentCondition = 0;

            List<int> relationIds = new List<int>();

            foreach (var rawCondition in conditionsList)
            {
                Int32.TryParse(rawCondition, out currentCondition);

                if (currentCondition < 1)
                    continue;

                var dbCondition = await _expertClient.GetConditionAsync(currentCondition);

                if (dbCondition?.Id > 0 || dbCondition?.SystemId == systemId)
                {
                    var newRelation = await _expertClient.AddRelationAsync(new RelationModel
                    {
                        SystemId = systemId,
                        ProductId = productId,
                        ConditionId = dbCondition.Id,
                        Weight = 100
                    });

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