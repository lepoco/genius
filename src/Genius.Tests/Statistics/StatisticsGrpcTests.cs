// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Tests.Statistics;

public class StatisticsGrpcTests
{
    private readonly DbContextOptions<Genius.Statistics.Data.Contexts.StatisticsContext> _databaseContextOptions;

    private readonly Genius.Statistics.Data.Contexts.StatisticsContext _databaseContext;

    public StatisticsGrpcTests()
    {
        _databaseContextOptions = new DbContextOptionsBuilder<Genius.Statistics.Data.Contexts.StatisticsContext>()
            .UseInMemoryDatabase(databaseName: "StatisticsDatabase")
            .Options;

        _databaseContext = new Genius.Statistics.Data.Contexts.StatisticsContext(_databaseContextOptions);

        FillDatabase(_databaseContext);
    }

    [Test]
    public void StatisticsAcceptPush()
    {
        // Arrange
        var mockLogger = new Mock<ILogger<Genius.Statistics.Services.GrpcStatisticsService>>();
        var mockContext = new Mock<Grpc.Core.ServerCallContext>();
        var mockDatabase = _databaseContext;

        // Initialize
        var service = new Genius.Statistics.Services.GrpcStatisticsService(mockLogger.Object, mockDatabase);

        // Act
        var response =
            service.Push(
                new Genius.Protocol.StatisticModel
                {
                    Id = 0,
                    Context = "system.export.2",
                    Type = Genius.Protocol.StatisticType.System,
                    CreatedAt = DateTime.Now.ToShortDateString()
                }, mockContext.Object);

        // Assert
        Assert.NotNull(response);
        Assert.AreEqual(1, response.Id);
    }

    private void FillDatabase(Genius.Statistics.Data.Contexts.StatisticsContext context)
    {
        context.Entries.Add(new Genius.Statistics.Data.Models.StatisticEntry
        {
            Context = "export.system.1",
            Type = Genius.Statistics.Data.Models.StatisticType.System
        });

        context.Entries.Add(new Genius.Statistics.Data.Models.StatisticEntry
        {
            Context = "import.system.1",
            Type = Genius.Statistics.Data.Models.StatisticType.System
        });

        context.SaveChanges();
    }
}
