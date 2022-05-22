// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Microsoft.EntityFrameworkCore;

namespace Genius.Statistics.Data.Contexts;

/// <summary>
/// Represents the database context for statistics.
/// </summary>
public class StatisticsContext : DbContext
{
    /// Entries in the database.
    public DbSet<Models.StatisticEntry> Entries { get; set; }

    public StatisticsContext(DbContextOptions<StatisticsContext> options) : base(options)
    {
    }
}

