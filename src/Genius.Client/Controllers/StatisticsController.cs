// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Threading.Tasks;
using Genius.Client.Interfaces;
using Genius.Protocol;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Controllers;

/// <summary>
/// Provides API for collecting telemetry.
/// </summary>
[ApiController]
[Route("api/[controller]")]
public class StatisticsController : ControllerBase
{
    private readonly ILogger<StatisticsController> _logger;

    private readonly IStatistics _statistics;

    public StatisticsController(ILogger<StatisticsController> logger, IStatistics statistics)
    {
        _logger = logger;
        _statistics = statistics;
    }

    [HttpPost]
    [Route("add")]
    public async Task<IActionResult> Add([FromRoute] int type, [FromRoute] string context)
    {
        if (type < 0)
            return StatusCode(400, "Unknown type");

        if (String.IsNullOrWhiteSpace(context))
            return StatusCode(400, "Context cannot be empty");

        var statisticType = type switch
        {
            1 => StatisticType.User,
            2 => StatisticType.System,
            3 => StatisticType.Event,
            _ => StatisticType.Unknown
        };

        var newEntry = _statistics.AddAsync(statisticType, context);

        return StatusCode(200, $"Entry {newEntry.Id} added");
    }
}
