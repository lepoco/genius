// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Threading.Tasks;
using Genius.Client.Interfaces;
using Genius.Protocol;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Services;

public class GrpcStatisticsService : IStatistics
{
    private readonly ILogger<GrpcStatisticsService> _logger;

    private readonly Genius.Protocol.Statistic.StatisticClient _grpcClient;

    public GrpcStatisticsService(ILogger<GrpcStatisticsService> logger, IChannel channel)
    {
        _logger = logger;
        _grpcClient = new Genius.Protocol.Statistic.StatisticClient(channel.GetStatisticsChannel());
    }

    public async Task<int> AddAsync(StatisticType type, string context)
    {
        var newEntry = await _grpcClient.PushAsync(new StatisticModel { Context = context.Trim().ToLower(), Type = type });

        return newEntry.Id;
    }
}
