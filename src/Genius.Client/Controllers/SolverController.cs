// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Collections.Generic;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using Genius.Client.Interfaces;
using Genius.Protocol;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Controllers;

/// <summary>
/// Provides the RESTful API for querying the specific Solver.
/// </summary>
[ApiController]
[Route("api/[controller]")]
public class SolverController : ControllerBase
{
    private readonly ILogger<SolverController> _logger;

    private readonly Solver.SolverClient _grpcClient;

    public SolverController(ILogger<SolverController> logger, IChannel channel)
    {
        _logger = logger;
        _grpcClient = new Solver.SolverClient(channel.GetGeniusChannel());
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


        IEnumerable<int> idsConfirming = FetchRawArray(HttpContext.Request.Form["confirming"]);
        IEnumerable<int> idsNegating = FetchRawArray(HttpContext.Request.Form["negating"]);
        IEnumerable<int> idsIndifferent = FetchRawArray(HttpContext.Request.Form["indifferent"]);

        var question = new SolverQuestion
        {
            SystemId = systemId,
            Multiple = HttpContext.Request.Form["multiple"] == "true" || HttpContext.Request.Form["multiple"] == "1",
            Confirming = { idsConfirming },
            Negating = { idsNegating },
            Indifferent = { idsIndifferent },
        };

        return await _grpcClient.AskAsync(question);
    }

    private IEnumerable<int> FetchRawArray(string rawArray)
    {
        rawArray = Regex.Replace(rawArray, "[^0-9,]", "");

        string[] idsRawArray = rawArray.Split(',');

        var idsList = new List<int>() { };

        foreach (var singleRawId in idsRawArray)
        {
            Int32.TryParse(singleRawId, out int conditionId);

            if (conditionId > 0 && !idsList.Contains(conditionId))
                idsList.Add(conditionId);
        }

        return idsList.ToArray();
    }
}
