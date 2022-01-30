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
            var insertedSystem = new ExpertModel
            {
                Name = HttpContext.Request.Form["name"],
                Description = HttpContext.Request.Form["description"],
                Question = HttpContext.Request.Form["question"]
            };

            if (String.IsNullOrEmpty(insertedSystem.Name) || String.IsNullOrEmpty(insertedSystem.Description))
            {
                return StatusCode(200, false);
            }

            var serverResponse = await _expertClient.CreateSystemAsync(insertedSystem);


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

        [HttpPost]
        [Route("product")]
        public async Task<IActionResult> InsertProduct()
        {
            return StatusCode(200, true);
        }

        [HttpPost]
        [Route("product/{guid}")]
        public async Task<IEnumerable<ProductModel>> GetAllProducts()
        {
            List<ProductModel> products = new List<ProductModel>();

            return products;
        }

        [HttpGet]
        [Route("product/{guid}/{id}")]
        public async Task<ProductModel> GetSingleProduct([FromRoute] string guid, [FromRoute] int id)
        {
            //return await _expertClient.GetSystemByGuidAsync(guid);
            return new ProductModel
            {
            };
        }

        [HttpPost]
        [Route("condition")]
        public async Task<IActionResult> InsertCondition()
        {
            return StatusCode(200, true);
        }

        [HttpPost]
        [Route("condition/{id}")]
        public async Task<IEnumerable<ConditionModel>> GetAllConditions([FromRoute] int id)
        {
            List<ConditionModel> products = new List<ConditionModel>();

            return products;
        }

        [HttpGet]
        [Route("condition/{systemId}/{id}")]
        public async Task<ConditionModel> GetSingleCondition([FromRoute] int systemId, [FromRoute] int id)
        {
            //return await _expertClient.GetSystemByGuidAsync(guid);
            return new ConditionModel
            {
            };
        }
    }
}