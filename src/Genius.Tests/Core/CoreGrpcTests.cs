// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Protocol;
using Genius.Services;

namespace Genius.Tests.Core;

public class CoreGrpcTests
{
    private readonly DbContextOptions<Genius.Data.Contexts.ExpertContext> _databaseContextOptions;

    private readonly Genius.Data.Contexts.ExpertContext _databaseContext;

    public CoreGrpcTests()
    {
        _databaseContextOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: "CoreDatabase")
            .Options;

        _databaseContext = new Genius.Data.Contexts.ExpertContext(_databaseContextOptions);

        FillDatabase(_databaseContext);
    }

    [Test]
    public async Task ExpertGrpcAcceptsCreatingSystems()
    {
        // Arrange
        var mockLogger = new Mock<ILogger<Genius.Services.GrpcExpertService>>();
        var mockContext = new Mock<Grpc.Core.ServerCallContext>();
        var mockDatabaseOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: $"CoreDatabase_{nameof(ExpertGrpcAcceptsCreatingSystems)}")
            .Options;
        var mockDatabase = new Genius.Data.Contexts.ExpertContext(mockDatabaseOptions);
        var mockExpertService = new GeniusService(mockDatabase);

        // Initialize
        var service = new Genius.Services.GrpcExpertService(mockLogger.Object, mockExpertService);

        // Act
        await service.Create(
                new ExpertModel
                {
                    Name = "ACT_SYSTEM_NAME_1",
                    Description = "ACT_SYSTEM_DESCRIPTION",
                    Question = "ACT_SYSTEM_QUESTION"
                }, mockContext.Object);

        await service.Create(
                new ExpertModel
                {
                    Name = "ACT_SYSTEM_NAME_2",
                    Description = "ACT_SYSTEM_DESCRIPTION",
                    Question = "ACT_SYSTEM_QUESTION"
                }, mockContext.Object);


        var response =
           await service.Create(
               new ExpertModel
               {
                   Name = "ACT_SYSTEM_NAME_3",
                   Description = "ACT_SYSTEM_DESCRIPTION",
                   Question = "ACT_SYSTEM_QUESTION"
               }, mockContext.Object);

        // Assert
        var expectedSystemId = 3;

        Assert.NotNull(response);
        Assert.AreEqual(expectedSystemId, response.Id);
    }

    [Test]
    public async Task ExpertGrpcAcceptsCreatingProducts()
    {
        // Arrange
        var mockLogger = new Mock<ILogger<Genius.Services.GrpcExpertService>>();
        var mockContext = new Mock<Grpc.Core.ServerCallContext>();
        var mockDatabaseOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: $"CoreDatabase_{nameof(ExpertGrpcAcceptsCreatingProducts)}")
            .Options;
        var mockDatabase = new Genius.Data.Contexts.ExpertContext(mockDatabaseOptions);
        var mockExpertService = new GeniusService(mockDatabase);

        // Initialize
        var service = new Genius.Services.GrpcExpertService(mockLogger.Object, mockExpertService);

        // Act
        var createdSystemResponse = await service.Create(
            new ExpertModel
            {
                Name = "ACT_SYSTEM_NAME_1",
                Description = "ACT_SYSTEM_DESCRIPTION",
                Question = "ACT_SYSTEM_QUESTION"
            }, mockContext.Object);

        await service.AddProduct(
            new ProductModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_PRODUCT_1"
            }, mockContext.Object);

        await service.AddProduct(
            new ProductModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_PRODUCT_2"
            }, mockContext.Object);

        var response = await service.AddProduct(
            new ProductModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_PRODUCT_3"
            }, mockContext.Object);

        // Assert
        var expectedProductId = 3;

        Assert.NotNull(response);
        Assert.AreEqual(expectedProductId, response.Id);
    }

    [Test]
    public async Task ExpertGrpcAcceptsCreatingConditions()
    {
        // Arrange
        var mockLogger = new Mock<ILogger<Genius.Services.GrpcExpertService>>();
        var mockContext = new Mock<Grpc.Core.ServerCallContext>();
        var mockDatabaseOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: $"CoreDatabase_{nameof(ExpertGrpcAcceptsCreatingConditions)}")
            .Options;
        var mockDatabase = new Genius.Data.Contexts.ExpertContext(mockDatabaseOptions);
        var mockExpertService = new GeniusService(mockDatabase);

        // Initialize
        var service = new Genius.Services.GrpcExpertService(mockLogger.Object, mockExpertService);

        // Act
        var createdSystemResponse = await service.Create(
            new ExpertModel
            {
                Name = "ACT_SYSTEM_NAME_1",
                Description = "ACT_SYSTEM_DESCRIPTION",
                Question = "ACT_SYSTEM_QUESTION"
            }, mockContext.Object);

        await service.AddCondition(
            new ConditionModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_CONDITION_1"
            }, mockContext.Object);

        await service.AddCondition(
            new ConditionModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_CONDITION_2"
            }, mockContext.Object);

        var response = await service.AddCondition(
            new ConditionModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_CONDITION_3"
            }, mockContext.Object);

        // Assert
        var expectedConditionId = 3;

        Assert.NotNull(response);
        Assert.AreEqual(expectedConditionId, response.Id);
    }

    [Test]
    public async Task ExpertGrpcAcceptsCreatingRelations()
    {
        // Arrange
        var mockLogger = new Mock<ILogger<Genius.Services.GrpcExpertService>>();
        var mockContext = new Mock<Grpc.Core.ServerCallContext>();
        var mockDatabaseOptions = new DbContextOptionsBuilder<Genius.Data.Contexts.ExpertContext>()
            .UseInMemoryDatabase(databaseName: $"CoreDatabase_{nameof(ExpertGrpcAcceptsCreatingRelations)}")
            .Options;
        var mockDatabase = new Genius.Data.Contexts.ExpertContext(mockDatabaseOptions);
        var mockExpertService = new GeniusService(mockDatabase);

        // Initialize
        var service = new Genius.Services.GrpcExpertService(mockLogger.Object, mockExpertService);

        // Act
        var createdSystemResponse = await service.Create(
            new ExpertModel
            {
                Name = "ACT_SYSTEM_NAME_1",
                Description = "ACT_SYSTEM_DESCRIPTION",
                Question = "ACT_SYSTEM_QUESTION"
            }, mockContext.Object);

        await service.AddCondition(
            new ConditionModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_CONDITION_1"
            }, mockContext.Object);

        await service.AddCondition(
            new ConditionModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_CONDITION_2"
            }, mockContext.Object);

        await service.AddProduct(
            new ProductModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_PRODUCT_1"
            }, mockContext.Object);

        await service.AddProduct(
            new ProductModel
            {
                SystemId = createdSystemResponse.Id,
                Name = "ACT_PRODUCT_2"
            }, mockContext.Object);

        await service.AddRelation(
            new RelationModel
            {
                SystemId = createdSystemResponse.Id,
                ProductId = 1,
                ConditionId = 1
            }, mockContext.Object);

        await service.AddRelation(
            new RelationModel
            {
                SystemId = createdSystemResponse.Id,
                ProductId = 1,
                ConditionId = 2
            }, mockContext.Object);

        var response = await service.AddRelation(
            new RelationModel
            {
                SystemId = createdSystemResponse.Id,
                ProductId = 2,
                ConditionId = 1
            }, mockContext.Object);

        // Assert
        var expectedRelationId = 3;

        Assert.NotNull(response);
        Assert.AreEqual(expectedRelationId, response.Id);
    }

    private void FillDatabase(Genius.Data.Contexts.ExpertContext context)
    {
        context.Systems.Add(new Genius.Data.Models.Expert.System
        {
            Name = "_TEST_EXPERT_SYSTEM",
            Description = "_TEST_DESCRIPTION",
            Guid = Guid.NewGuid().ToString(),
            Question = "_TEST_QUESTION",
            Type = Genius.Data.Models.Expert.SystemType.Conditional
        });

        context.Products.Add(new Genius.Data.Models.Expert.Product
        {
            SystemId = 1,
            Name = "_TEST_EXPERT_PRODUCT_1",
            Description = "_TEST_DESCRIPTION",
            Notes = "_TEST_NOTES",
        });

        context.Products.Add(new Genius.Data.Models.Expert.Product
        {
            SystemId = 1,
            Name = "_TEST_EXPERT_PRODUCT_2",
            Description = "_TEST_DESCRIPTION",
            Notes = "_TEST_NOTES",
        });

        context.Conditions.Add(new Genius.Data.Models.Expert.Condition
        {
            SystemId = 1,
            Name = "_TEST_EXPERT_CONDITION_1",
            Description = "_TEST_DESCRIPTION",
        });

        context.Conditions.Add(new Genius.Data.Models.Expert.Condition
        {
            SystemId = 1,
            Name = "_TEST_EXPERT_CONDITION_2",
            Description = "_TEST_DESCRIPTION",
        });

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
            CondiotionId = 2,
            ProductId = 1,
            Type = Genius.Data.Models.Expert.RelationType.Compliance,
            Weight = 100
        });

        context.Relations.Add(new Genius.Data.Models.Expert.Relation
        {
            SystemId = 1,
            CondiotionId = 1,
            ProductId = 2,
            Type = Genius.Data.Models.Expert.RelationType.Compliance,
            Weight = 100
        });

        context.SaveChanges();
    }
}
