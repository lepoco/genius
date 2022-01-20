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


            return StatusCode(200, true);
        }

        [HttpGet]
        [Route("system/{guid}")]
        public async Task<ExpertModel> GetSingleSystem([FromRoute] string guid)
        {
            return await _expertClient.GetSystemByGuidAsync(guid);
        }
    }
}