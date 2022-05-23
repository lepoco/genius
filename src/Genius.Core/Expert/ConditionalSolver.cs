// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Linq;
using System.Threading.Tasks;
using Genius.Core.Data.Models.Expert;
using Genius.Core.Expert.Interfaces;
using Microsoft.EntityFrameworkCore;

namespace Genius.Core.Expert;

/// <summary>
/// Solves questions on the basis of the provided <see cref="Condition"/>'s by searching for a suitable <see cref="Product"/> or by returning the next <see cref="Condition"/> to ask.
/// </summary>
public class ConditionalSolver : SolverBase
{
    private ISolverQuestion _question;

    /// <inheritdoc />
    public override async Task<ISolverResponse> Solve(ISolverQuestion solverQuestion)
    {
        _question = solverQuestion;

        if (solverQuestion.SystemId < 1)
            return GenerateEmptyResponse();

        if (solverQuestion.IsEmpty())
            return await GenerateFirstResponse();

        // All available products for current state
        var resultingProductsIds = await FindResultingProductsIds();

        // All available conditions for resulting products
        var nextConditionsIds = await FindNextConditions(resultingProductsIds);

        // New condition found, display a question
        if (nextConditionsIds.Any())
            return GenerateResponse(nextConditionsIds, new int[] { });

        // Product found, display a result
        if (resultingProductsIds.Any())
            return GenerateResponse(new int[] { }, resultingProductsIds);

        // TODO: If neither the next condition nor the product is found, consider other options.
        // For example, ask again for the most changing condition.

        // Get one by one all answered relations for NO, and predict which one removes the most possible solutions

        return GenerateEmptyResponse();
    }

    private async Task<int[]> FindNextConditions(int[] availableProductsId)
    {
        // If less than the next two combinations are possible, return empty
        if (availableProductsId.Length < 2)
            return new int[] { };

        // Select all possible relations for available products
        var relationsForAvailableProducts = await ExpertContext.Relations
            .Where(relation => availableProductsId.Contains(relation.ProductId)).ToArrayAsync();

        var filteredAvailableRelations = relationsForAvailableProducts;

        // Remove all relations that already occurs as confirming
        if (_question.Confirming.Any())
            filteredAvailableRelations = filteredAvailableRelations
                .Where(relation => !_question.Confirming.Contains(relation.ConditionId))
                .ToArray();

        // Remove all relations that already occurs as negating
        if (_question.Negating.Any())
            filteredAvailableRelations = filteredAvailableRelations
                .Where(relation => !_question.Negating.Contains(relation.ConditionId))
                .ToArray();

        // Group relations by condition and order them by most common
        // and remove all which count is equal for products count, so condition occurs for every product
        var availableConditionsIds = filteredAvailableRelations
            .GroupBy(relation => relation.ConditionId)
            .OrderByDescending(group => group.Count())
            .Where(group => group.Count() < availableProductsId.Length)
            .Select(group => group.Key)
            .ToArray();

        if (!availableConditionsIds.Any())
            return new int[] { };


        // Delete all indifferent, if this makes it impossible to ask a condition, maybe we should ask for one again
        availableConditionsIds =
            availableConditionsIds.Where(con => !_question.Indifferent.Contains(con)).ToArray();

        return availableConditionsIds;
    }

    private async Task<int[]> FindResultingProductsIds()
    {
        // Take all system relations
        var systemRelations = await ExpertContext.Relations.Where(rel => rel.SystemId == _question.SystemId)
            .ToArrayAsync();

        // Group relations by products
        var relationGroups = systemRelations.GroupBy(rel => rel.ProductId);

        // If there are confirming conditions, get groups that contain it
        if (_question.Confirming.Any())
            relationGroups = relationGroups
                .Where(group => group.Any(rel => _question.Confirming.Contains(rel.ConditionId)))
                .ToArray();

        // If there are negating conditions, remove groups that contain it
        if (_question.Negating.Any())
            relationGroups = relationGroups
                .Where(group => !group.Any(rel => _question.Negating.Contains(rel.ConditionId)))
                .ToArray();

        // Order by groups with the most conditions and select product IDs.
        return relationGroups.OrderByDescending(group => group.Count()).Select(g => g.Key).ToArray();
    }

    /// <summary>
    /// Looks for the most common condition in the expert system.
    /// </summary>
    /// <returns>ID of the most common condition in the array, if it exists.</returns>
    private async Task<SolverResponse> GenerateFirstResponse()
    {
        // Get all system conditions and group them by most popular
        var mostCommonConditions = await ExpertContext.Relations
            .Where(relation => relation.SystemId == _question.SystemId)
            .GroupBy(q => q.ConditionId)
            .OrderByDescending(gp => gp.Count())
            .Select(g => g.Key) // Key of the group, i.e. the key by which it is grouped
            .ToArrayAsync();

        var productsCount = await ExpertContext.Products.Where(prod => prod.SystemId == _question.SystemId)
            .CountAsync();
        var nextConditionId = 0;

        // If all Products are related to the most common condition, skip it.
        foreach (var singleConditionId in mostCommonConditions)
        {
            if (await ExpertContext.Relations.Where(rel => rel.ConditionId == singleConditionId).CountAsync() >=
                productsCount)
                continue;

            nextConditionId = singleConditionId;

            break;
        }

        return nextConditionId < 1
            ? GenerateEmptyResponse()
            : GenerateResponse(new[] { nextConditionId }, new int[] { });
    }

    /// <summary>
    /// Generates an empty answer, with no conditions or products.
    /// </summary>
    private SolverResponse GenerateEmptyResponse()
    {
        return GenerateResponse(new int[] { }, new int[] { });
    }

    /// <summary>
    /// Generates a new response based on the parameters provided.
    /// </summary>
    private SolverResponse GenerateResponse(int[] nextConditions, int[] resultingProducts,
        SolverStatus status = SolverStatus.Unknown)
    {
        if (status == SolverStatus.Unknown && nextConditions.Any())
            status = SolverStatus.NewQuestion;

        if (status == SolverStatus.Unknown && resultingProducts.Any())
            status = SolverStatus.Solved;

        return new SolverResponse
        {
            SystemId = _question.SystemId,
            IsMultiple = _question.IsMultiple,
            Status = status,
            IsSolved = resultingProducts.Any(),
            ResultingProducts = resultingProducts,
            NextConditions = nextConditions
        };
    }
}
