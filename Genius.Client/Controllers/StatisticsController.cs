using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using System.Collections.Generic;

namespace Genius.Client.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class StatisticsController
    {
        private readonly ILogger<StatisticsController> _logger;

        public StatisticsController(ILogger<StatisticsController> logger)
        {
            _logger = logger;
        }

        [HttpGet]
        public IEnumerable<int> Get()
        {
            return new List<int> { 1, 2, 3, 4 };
        }
    }
}
