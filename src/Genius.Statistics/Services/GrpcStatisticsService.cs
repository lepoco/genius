// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Linq;
using System.Threading.Tasks;
using Genius.Statistics.Data.Contexts;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;

namespace Genius.Statistics.Services;

public class GrpcStatisticsService : Statistic.StatisticBase
{
    private readonly ILogger<GrpcStatisticsService> _logger;

    private readonly StatisticsContext _context;

    public GrpcStatisticsService(ILogger<GrpcStatisticsService> logger, StatisticsContext context)
    {
        _logger = logger;
        _context = context;
    }

    public override async Task<StatisticResponseModel> Push(StatisticModel request, ServerCallContext context)
    {
        _logger.LogInformation($"New statistics request: {request?.Type}, {request?.Context}");

        if (request == null)
            return new StatisticResponseModel { Id = 0 };

        if (request.Type == StatisticType.Unknown)
            return new StatisticResponseModel { Id = 0 };

        if (String.IsNullOrWhiteSpace(request.Context))
            return new StatisticResponseModel { Id = 0 };

        // Consider writing reflection
        var statisticType = request.Type switch
        {
            GeniusProtocol.StatisticType.User => Data.Models.StatisticType.User,
            GeniusProtocol.StatisticType.System => Data.Models.StatisticType.System,
            GeniusProtocol.StatisticType.Event => Data.Models.StatisticType.Event,
            _ => Data.Models.StatisticType.Unknown
        };

        var insertedEntry = new Data.Models.StatisticEntry
        {
            Context = request.Context.Trim(),
            Type = statisticType,
            CreatedAt = DateTime.Now
        };

        _context.Entries.Add(insertedEntry);

        await _context.SaveChangesAsync();

        return new StatisticResponseModel { Id = insertedEntry?.Id ?? 0 };
    }

    public override async Task<StatisticResponseModel> Delete(StatisticLookupModel request, ServerCallContext context)
    {
        if (request == null)
            return new StatisticResponseModel { Id = 0 };

        if (request.Id < 1)
            return new StatisticResponseModel { Id = 0 };

        var entry = await _context.Entries.Where(entry => entry.Id == request.Id).FirstOrDefaultAsync();

        if (entry == null || entry.Id < 1)
            return new StatisticResponseModel { Id = 0 };

        _context.Entries.Remove(entry);

        await _context.SaveChangesAsync();

        return new StatisticResponseModel { Id = request.Id };
    }

    public override async Task<StatisticModel> GetSingle(StatisticLookupModel request, ServerCallContext context)
    {
        if (request == null)
            return new StatisticModel { Id = 0 };

        if (request.Id < 1)
            return new StatisticModel { Id = 0 };

        var entry = await _context.Entries.Where(entry => entry.Id == request.Id).FirstOrDefaultAsync();

        if (entry == null || entry.Id < 1)
            return new StatisticModel { Id = 0 };

        var statisticType = entry.Type switch
        {
            Data.Models.StatisticType.User => GeniusProtocol.StatisticType.User,
            Data.Models.StatisticType.System => GeniusProtocol.StatisticType.System,
            Data.Models.StatisticType.Event => GeniusProtocol.StatisticType.Event,
            _ => GeniusProtocol.StatisticType.Unknown
        };

        return new StatisticModel
        {
            Id = entry.Id,
            Type = statisticType,
            Context = entry.Context,
            CreatedAt = entry.CreatedAt.ToShortDateString()
        };
    }

    public override async Task GetRange(StatisticRangeLookupModel request, IServerStreamWriter<StatisticModel> responseStream, ServerCallContext context)
    {
        if (request == null)
            return;

        if (request.Type == StatisticType.Unknown)
            return;

        var lookupType = request.Type switch
        {
            GeniusProtocol.StatisticType.User => Data.Models.StatisticType.User,
            GeniusProtocol.StatisticType.System => Data.Models.StatisticType.System,
            GeniusProtocol.StatisticType.Event => Data.Models.StatisticType.Event,
            _ => Data.Models.StatisticType.Unknown
        };

        // TODO: Datetime

        var savedEntries = _context.Entries.Where(entry => entry.Type == lookupType);

        foreach (Data.Models.StatisticEntry singleEntry in savedEntries)
        {
            var statisticType = singleEntry.Type switch
            {
                Data.Models.StatisticType.User => GeniusProtocol.StatisticType.User,
                Data.Models.StatisticType.System => GeniusProtocol.StatisticType.System,
                Data.Models.StatisticType.Event => GeniusProtocol.StatisticType.Event,
                _ => GeniusProtocol.StatisticType.Unknown
            };

            await responseStream.WriteAsync(new StatisticModel
            {
                Id = singleEntry.Id,
                Type = statisticType,
                Context = singleEntry.Context,
                CreatedAt = singleEntry.CreatedAt.ToShortDateString(),
            });
        }
    }

    public override async Task GetContext(StatisticContextLookupModel request, IServerStreamWriter<StatisticModel> responseStream, ServerCallContext context)
    {
        if (request == null)
            return;

        if (String.IsNullOrWhiteSpace(request.Context))
            return;

        var entryContext = request.Context.Trim();

        // TODO: Datetime

        IQueryable<Data.Models.StatisticEntry> savedEntries;

        if (request.Type != StatisticType.Unknown)
        {
            var lookupType = request.Type switch
            {
                GeniusProtocol.StatisticType.User => Data.Models.StatisticType.User,
                GeniusProtocol.StatisticType.System => Data.Models.StatisticType.System,
                GeniusProtocol.StatisticType.Event => Data.Models.StatisticType.Event,
                _ => Data.Models.StatisticType.Unknown
            };

            savedEntries = _context.Entries.Where(entry => entry.Type == lookupType && entry.Context == entryContext);
        }
        else
        {
            savedEntries = _context.Entries.Where(entry => entry.Context == entryContext);
        }

        foreach (Data.Models.StatisticEntry singleEntry in savedEntries)
        {
            var statisticType = singleEntry.Type switch
            {
                Data.Models.StatisticType.User => GeniusProtocol.StatisticType.User,
                Data.Models.StatisticType.System => GeniusProtocol.StatisticType.System,
                Data.Models.StatisticType.Event => GeniusProtocol.StatisticType.Event,
                _ => GeniusProtocol.StatisticType.Unknown
            };

            await responseStream.WriteAsync(new StatisticModel
            {
                Id = singleEntry.Id,
                Type = statisticType,
                Context = singleEntry.Context,
                CreatedAt = singleEntry.CreatedAt.ToShortDateString(),
            });
        }
    }
}

