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
            {
                return StatusCode(200, false);
            }

            var serverResponse = await _expertClient.CreateSystemAsync(newSystem);


            return StatusCode(200, "success");
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

            var newProduct = new ProductModel
            {
                SystemId = systemId,
                Name = HttpContext.Request.Form["name"],
                Description = HttpContext.Request.Form["description"],
                Notes = HttpContext.Request.Form["notes"]
            };

            var serverResponse = await _expertClient.AddProductAsync(newProduct);

            return StatusCode(200, serverResponse);
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

            var serverResponse = await _expertClient.AddConditionAsync(newCondition);

            return StatusCode(200, serverResponse);
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
    }
}