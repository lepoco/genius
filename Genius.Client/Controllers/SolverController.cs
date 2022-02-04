// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Services;
using GeniusProtocol;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using System;
using System.Threading.Tasks;

namespace Genius.Client.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class SolverController : ControllerBase
    {
        private readonly ILogger<SolverController> _logger;

        private readonly GrpcSolverClientService _solverClient;

        public SolverController(ILogger<SolverController> logger, GrpcSolverClientService solverClient)
        {
            _logger = logger;
            _solverClient = solverClient;
        }

        [HttpGet]
        public IActionResult Get()
        {
            return StatusCode(404, "The service does not support direct reading.");
        }

        [HttpPost]
        [Route("ask")]
        public async Task<SolverResponse> AskQuestion()
        {
            int systemId = Int32.Parse(HttpContext.Request.Form["systemId"]);

            if (systemId < 1)
                return new SolverResponse
                { IsSolved = false, Multiple = true, NextCondition = 0, Products = { }, Status = 0, SystemId = 0 };


            string rawConfirming = HttpContext.Request.Form["confirming"];
            string rawNegating = HttpContext.Request.Form["negating"];
            string rawIndifferent = HttpContext.Request.Form["indifferent"];

            var question = new SolverQuestion
            {
                SystemId = systemId,
                Multiple = HttpContext.Request.Form["multiple"] == "true" || HttpContext.Request.Form["multiple"] == "1",
                Confirming = { FetchRawArray(rawConfirming) },
                Negating = { FetchRawArray(rawNegating) },
                Indifferent = { FetchRawArray(rawIndifferent) },
            };

            return await _solverClient.AskAsync(question);
        }

        private int[] FetchRawArray(string rawArray)
        {
            return new int[] { };
        }
    }
}
