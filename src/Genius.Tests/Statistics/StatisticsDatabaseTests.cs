// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Tests.Statistics;

public class StatisticsDatabaseTests
{
    private readonly DbContextOptions<Genius.Statistics.Data.Contexts.StatisticsContext> _databaseContextOptions;

    public StatisticsDatabaseTests()
    {
        _databaseContextOptions = new DbContextOptionsBuilder<Genius.Statistics.Data.Contexts.StatisticsContext>()
            .UseInMemoryDatabase(databaseName: "StatisticsDatabase")
            .Options;
    }

    [Test]
    public void StatisticsDatabaseCanAddElements()
    {
        // Insert seed data into the database using one instance of the context
        using (var context = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions))
        {
            context.Entries.Add(new Genius.Statistics.Data.Models.StatisticEntry
            {
                Context = "mockContext",
                CreatedAt = DateTime.Now,
                Type = Genius.Statistics.Data.Models.StatisticType.System
            });

            context.SaveChanges();
        }

        var entriesCount = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions).Entries.Count();

        Assert.NotNull(entriesCount);
        Assert.AreEqual(entriesCount, 1);
    }

    [Test]
    public void StatisticsDatabaseIncrementKeys()
    {
        // Insert seed data into the database using one instance of the context
        using (var context = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions))
        {
            context.Entries.Add(new Genius.Statistics.Data.Models.StatisticEntry
            {
                Context = "mockContext",
                CreatedAt = DateTime.Now,
                Type = Genius.Statistics.Data.Models.StatisticType.System
            });

            context.SaveChanges();
        }

        var firstEntry = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions).Entries.First();

        Assert.NotNull(firstEntry);
        Assert.AreEqual(firstEntry.Id, 1);
    }

    [Test]
    public void StatisticsDatabaseSetsDate()
    {
        // Insert seed data into the database using one instance of the context
        using (var context = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions))
        {
            context.Entries.Add(new Genius.Statistics.Data.Models.StatisticEntry
            {
                Context = "mockContext",
                Type = Genius.Statistics.Data.Models.StatisticType.System
            });

            context.SaveChanges();
        }

        var firstEntry = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions).Entries.First();
        var timeNow = DateTime.Now;

        Assert.NotNull(firstEntry);
        Assert.That(firstEntry.CreatedAt, Is.EqualTo(timeNow).Within(TimeSpan.FromHours(3.0)));
    }
}

