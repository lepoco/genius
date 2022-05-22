// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Tests.Core;

public class CoreDatabaseTests
{
    private readonly DbContextOptions<Genius.Data.Contexts.ExpertContext> _databaseContextOptions;

    private readonly Genius.Data.Contexts.ExpertContext _databaseContext;

    public CoreDatabaseTests()
    {
        _databaseContextOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: "CoreDatabase")
            .Options;

        _databaseContext = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions);
    }

    [Test]
    public void CoreDatabaseCanAddSystems()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Systems.Add(new Genius.Data.Models.Expert.System
            {
                Name = "_TEST_EXPERT_SYSTEM",
                Description = "_TEST_DESCRIPTION",
                Guid = Guid.NewGuid().ToString(),
                Question = "_TEST_QUESTION",
                Type = Genius.Data.Models.Expert.SystemType.Conditional
            });

            context.Systems.Add(new Genius.Data.Models.Expert.System
            {
                Name = "_TEST_EXPERT_SYSTEM",
                Description = "_TEST_DESCRIPTION",
                Guid = Guid.NewGuid().ToString(),
                Question = "_TEST_QUESTION",
                Type = Genius.Data.Models.Expert.SystemType.Conditional
            });

            context.SaveChanges();
        }

        var expectedSystems = 2;
        var systemsCount = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Systems.Count();

        Assert.NotNull(systemsCount);
        Assert.AreEqual(systemsCount, expectedSystems);
    }

    [Test]
    public void CoreDatabaseCanAddProducts()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Products.Add(new Genius.Data.Models.Expert.Product
            {
                SystemId = 1,
                Name = "_TEST_EXPERT_PRODUCT",
                Description = "_TEST_DESCRIPTION",
                Notes = "_TEST_NOTES",
            });

            context.Products.Add(new Genius.Data.Models.Expert.Product
            {
                SystemId = 1,
                Name = "_TEST_EXPERT_PRODUCT",
                Description = "_TEST_DESCRIPTION",
                Notes = "_TEST_NOTES",

            });

            context.SaveChanges();
        }

        var expectedProducts = 2;
        var productsCount = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Products.Count();

        Assert.NotNull(productsCount);
        Assert.AreEqual(productsCount, expectedProducts);
    }

    [Test]
    public void CoreDatabaseCanAddConditions()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Conditions.Add(new Genius.Data.Models.Expert.Condition
            {
                SystemId = 1,
                Name = "_TEST_EXPERT_CONDITION",
                Description = "_TEST_DESCRIPTION",
            });

            context.Conditions.Add(new Genius.Data.Models.Expert.Condition
            {
                SystemId = 1,
                Name = "_TEST_EXPERT_CONDITION",
                Description = "_TEST_DESCRIPTION",

            });

            context.SaveChanges();
        }

        var expectedConditions = 2;
        var conditionsCount = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Conditions.Count();

        Assert.NotNull(conditionsCount);
        Assert.AreEqual(conditionsCount, expectedConditions);
    }

    [Test]
    public void CoreDatabaseCanAddRelations()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Relations.Add(new Genius.Data.Models.Expert.Relation
            {
                SystemId = 1,
                CondiotionId = 1,
                ProductId = 1,
                Type = Genius.Data.Models.Expert.RelationType.Compliance,
                Weight = 100
            });

            context.Relations.Add(new Genius.Data.Models.Expert.Relation
            {
                SystemId = 1,
                CondiotionId = 1,
                ProductId = 1,
                Type = Genius.Data.Models.Expert.RelationType.Compliance,
                Weight = 100
            });

            context.SaveChanges();
        }

        var expectedRelations = 2;
        var relationsCount = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Relations.Count();

        Assert.NotNull(relationsCount);
        Assert.AreEqual(relationsCount, expectedRelations);
    }

    [Test]
    public void CoreDatabaseDefaultSystemTypeIsValid()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Systems.Add(new Genius.Data.Models.Expert.System
            {
                Name = "_TEST_EXPERT_SYSTEM",
                Description = "_TEST_DESCRIPTION",
                Guid = Guid.NewGuid().ToString(),
                Question = "_TEST_QUESTION",
            });

            context.SaveChanges();
        }

        var expectedType = Genius.Data.Models.Expert.SystemType.Conditional;
        var firstSystem = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Systems.First();

        Assert.NotNull(firstSystem);
        Assert.AreEqual(firstSystem.Type, expectedType);
    }

    [Test]
    public void CoreDatabaseDefaultSystemAddsDates()
    {
        using (var context = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions))
        {
            context.Systems.Add(new Genius.Data.Models.Expert.System
            {
                Name = "_TEST_EXPERT_SYSTEM",
                Description = "_TEST_DESCRIPTION",
                Guid = Guid.NewGuid().ToString(),
                Question = "_TEST_QUESTION",
            });

            context.SaveChanges();
        }

        var timeNow = DateTime.Now;
        var firstSystem = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions).Systems.First();

        Assert.NotNull(firstSystem);
        Assert.That(firstSystem.CreatedAt, Is.EqualTo(timeNow).Within(TimeSpan.FromHours(3.0)));
        Assert.That(firstSystem.UpdatedAt, Is.EqualTo(timeNow).Within(TimeSpan.FromHours(3.0)));
    }
}
