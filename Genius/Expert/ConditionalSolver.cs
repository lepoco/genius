// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using Microsoft.EntityFrameworkCore;
using System.Linq;
using System.Threading.Tasks;

namespace Genius.Expert
{
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

            if (solverQuestion.SystemId < 1) return GenerateEmptyResponse();

            if (solverQuestion.IsEmpty()) return await GenerateFirstResponse();

            // All available products for current state
            var resultingProductsIds = await FindResultingProductsIds();

            // All available conditions for resulting products
            var nextConditionsIds = await FindNextConditions(resultingProductsIds);

            // New condition found, display a question
            if (nextConditionsIds.Any()) return GenerateResponse(nextConditionsIds, new int[] { });

            // Product found, display a result
            if (resultingProductsIds.Any()) return GenerateResponse(new int[] { }, resultingProductsIds);

            // TODO: If neither the next condition nor the product is found, consider other options.
            // For example, ask again for the most changing condition.

            // Get one by one all answered relations for NO, and predict which one removes the most possible solutions

            return GenerateEmptyResponse();
        }

        private async Task<int[]> FindNextConditions(int[] availableProductsId)
        {
            // If less than the next two combinations are possible, return empty
            if (availableProductsId.Length < 2) return new int[] { };

            // Select all conditions
            var availableConditionsIds = await ExpertContext.Relations
                .Where(relation => relation.SystemId == _question.SystemId &&
                                   availableProductsId.Contains(relation.ProductId) &&
                                   !_question.Confirming.Contains(relation.CondiotionId))
                .GroupBy(relation => relation.CondiotionId)
                .Select(g => g.Key)
                .ToArrayAsync();

            // DO TODAY
            //var condd = await ExpertContext.Conditions.Where()

            if (!availableConditionsIds.Any()) return new int[] { };

            //var availableConditions = await ExpertContext.Conditions.Where(con => availableConditionsIds.Contains(con.Id)).ToArrayAsync();

            // Group all available relations
            var availableRelations = await ExpertContext.Relations
                .Where(rel => availableProductsId.Contains(rel.ProductId))
                .ToArrayAsync();

            var availableRelationGroups = availableRelations.GroupBy(rel => rel.ProductId);

            // Only where confirmed
            if (_question.Confirming.Any())
                availableRelationGroups = availableRelationGroups
                    .Where(group => group.Any(rel => _question.Confirming.Contains(rel.CondiotionId)))
                    .ToArray();

            var filteredAvailableProducts = availableRelationGroups.Where(group =>
                    group.Any(rel => availableConditionsIds.Contains(rel.CondiotionId)))
                .Select(g => g.Key)
                .ToArray();

            if (!filteredAvailableProducts.Any()) return new int[] { };


            // TODO: What about indifferent?
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
                    .Where(group => group.Any(rel => _question.Confirming.Contains(rel.CondiotionId)))
                    .ToArray();

            // If there are negating conditions, remove groups that contain it
            if (_question.Negating.Any())
                relationGroups = relationGroups
                    .Where(group => !group.Any(rel => _question.Negating.Contains(rel.CondiotionId)))
                    .ToArray();

            // Order by groups with the most conditions and select product IDs.
            return relationGroups.OrderByDescending(group => group.Count()).Select(g => g.Key).ToArray();
        }

        //private async Task<Product[]> FindResultingProducts()
        //{
        //    Test();
        //    // TODO: This array can be pretty huge
        //    // Select all relations which don't contradict
        //    var matchedRelations = await ExpertContext.Relations.Where(relation =>
        //            relation.SystemId == _question.SystemId && !_question.Negating.Contains(relation.CondiotionId))
        //        .ToArrayAsync();

        //    // Select product in which relations does not contains contradict condition
        //    //var availableRelationsIds = await ExpertContext.Relations
        //    //    .Where(relation => relation.SystemId == _question.SystemId)
        //    //    .GroupBy(rel => rel.ProductId)
        //    //    .Where(group => group.Any(rel => !_question.Negating.Contains(rel.CondiotionId)))
        //    //    .SelectMany(group => group).ToArrayAsync();

        //    // Select all relations which are compatible
        //    if (_question.Confirming.Any())
        //        matchedRelations = matchedRelations
        //            .Where(relation => _question.Confirming.Contains(relation.CondiotionId))
        //            .ToArray();

        //    // Sort by most common
        //    var matchedProducts = matchedRelations
        //        .GroupBy(q => q.ProductId)
        //        .OrderByDescending(gp => gp.Count())
        //        .Select(g => g.Key)
        //        .ToArray();

        //    var availableProducts = await ExpertContext.Products
        //        .Where(product => matchedProducts.Contains(product.Id)).ToArrayAsync();

        //    // Create matrix of products with its conditions count
        //    var productsWeight = new Dictionary<int, int>();

        //    foreach (var singleRelation in matchedRelations)
        //    {
        //        if (productsWeight.ContainsKey(singleRelation.ProductId))
        //            productsWeight[singleRelation.ProductId]++;
        //        else
        //            productsWeight.Add(singleRelation.ProductId, 1);
        //    }

        //    // Get only products which fulfill current conditions count
        //    productsWeight = productsWeight.Where(i => i.Value >= _question.Confirming.Count())
        //        .ToDictionary(i => i.Key, i => i.Value);

        //    // Order by most common??
        //    //productsWeight = productsWeight.OrderByDescending(x => x.Value).ToDictionary(x => x.Key, x => x.Value);

        //    var matchedProductsIds = new List<int>(productsWeight.Keys);

        //    return await ExpertContext.Products.Where(product => matchedProductsIds.Contains(product.Id))
        //        .ToArrayAsync();
        //}

        ///// <summary>
        ///// Finds the next condition based on the previous conditions. If it doesn't find a new one, we should return the products.
        ///// </summary>
        ///// <param name="systemId">The system in which the <see cref="Condition"/> is to be found.</param>
        ///// <param name="confirmingConditions">Confirming conditions represent logical <see langword="AND"/>.</param>
        ///// <param name="negatingConditions">Negating conditions represent logical <see langword="NOT"/>.</param>
        ///// <param name="indifferentConditions">Indifferent conditions represent logical <see langword="OR"/>.</param>
        ///// <returns></returns>
        //public async Task<IEnumerable<Condition>> FindNextCondition(int systemId, IEnumerable<int> confirmingConditions,
        //    IEnumerable<int> negatingConditions, IEnumerable<int> indifferentConditions)
        //{
        //    var conditions = new List<Condition>();

        //    var matchingProducts = await GetMatchingProducts(systemId,
        //        confirmingConditions as int[] ?? confirmingConditions.ToArray(),
        //        negatingConditions as int[] ?? negatingConditions.ToArray(),
        //        indifferentConditions as int[] ?? indifferentConditions.ToArray());

        //    // If only one product matches the current pool, don't return anything because that's the result.
        //    var productsEnumerable = matchingProducts.ToList();
        //    if (productsEnumerable?.Count() < 1) return conditions; // find which condition changes the most

        //    var matchedProductsIds = productsEnumerable.Select(product => product.Id).ToList();

        //    var availableConditions = await ExpertContext.Relations
        //        .Where(relation => relation.SystemId == systemId && matchedProductsIds.Contains(relation.ProductId) &&
        //                           !confirmingConditions.Contains(relation.CondiotionId))
        //        .GroupBy(relation => relation.CondiotionId)
        //        .Select(g => g.Key)
        //        .ToListAsync();

        //    if (!availableConditions.Any()) return conditions;

        //    var nextId = availableConditions.First();

        //    if (nextId < 1) return conditions;

        //    var nextCondition = await ExpertContext.Conditions.Where(condition => condition.Id == nextId).FirstAsync();

        //    if (nextCondition.Id > 0) conditions.Add(nextCondition);

        //    return conditions;
        //}

        ///// <summary>
        ///// Finds products that meet expectations.
        ///// </summary>
        ///// <param name="systemId">The system in which the <see cref="Product"/> is to be found.</param>
        ///// <param name="multiple">Decides whether we can have multiple results.</param>
        ///// <param name="confirmingConditions">Confirming conditions represent logical <see langword="AND"/>.</param>
        ///// <param name="negatingConditions">Negating conditions represent logical <see langword="NOT"/>.</param>
        ///// <param name="indifferentConditions">Indifferent conditions represent logical <see langword="OR"/>.</param>
        ///// <returns></returns>
        //public async Task<IEnumerable<Product>> FindProducts(int systemId, int[] confirmingConditions,
        //    int[] negatingConditions, int[] indifferentConditions)
        //{
        //    return await GetMatchingProducts(systemId, confirmingConditions, negatingConditions, indifferentConditions);
        //}

        //// Good Old Fashioned AI
        //// Symbolic AI - symbols, logic
        //// Symbols are nouns, and Relations are adjectives or verbs
        //// Knowledge base - contains all symbols and relations

        //// Symbols - car, man
        //// Logical connectives - AND OR
        //// Relations - Drives, Old
        //// Propositions logic
        //// Truth table

        //// Inference - finding new propositions based on knowledge base

        ///// <summary>
        ///// It takes the first (most popular), <see cref="Condition"/> for a given <see cref="Data.Models.Expert.System"/>.
        ///// </summary>
        ///// <param name="systemId">The system in which the <see cref="Condition"/> is to be found.</param>
        ///// <returns>List of next <see cref="Condition"/>'s to query.</returns>
        //public async Task<IEnumerable<Condition>> GetFirstCondition(int systemId)
        //{
        //    var conditions = new List<Condition>();

        //    var mostCommonRelations = await ExpertContext.Relations.Where(relation => relation.SystemId == systemId)
        //        .GroupBy(q => q.CondiotionId)
        //        .OrderByDescending(gp => gp.Count())
        //        .Take(1)
        //        .Select(g => g.Key)
        //        .ToListAsync();

        //    if (!mostCommonRelations.Any()) return conditions;

        //    var conditionId = mostCommonRelations.First();

        //    var mostCommonCondition = await ExpertContext.Conditions.Where(con => con.Id == conditionId).FirstAsync();

        //    if (mostCommonCondition?.Id < 1) return conditions;

        //    conditions.Add(mostCommonCondition);

        //    return conditions;
        //}

        //private async Task<IEnumerable<Product>> GetMatchingProducts(int systemId, int[] confirmingConditions,
        //    int[] negatingConditions, int[] indifferentConditions)
        //{
        //    List<Relation> matchedRelations;

        //    // TODO: This array can be pretty huge
        //    // Select from database all relations which don't contradict
        //    matchedRelations = await ExpertContext.Relations.Where(relation =>
        //            relation.SystemId == systemId && !negatingConditions.Contains(relation.CondiotionId))
        //        .ToListAsync();

        //    // If there's confirming, get only them
        //    var conditionsEnumerable = confirmingConditions as int[] ?? confirmingConditions.ToArray();

        //    if (conditionsEnumerable.Any())
        //        matchedRelations = matchedRelations
        //            .Where(relation => conditionsEnumerable.Contains(relation.CondiotionId))
        //            .ToList();

        //    // Create matrix of products with its conditions count
        //    var productsWeight = new Dictionary<int, int>();

        //    foreach (var singleRelation in matchedRelations)
        //    {
        //        if (productsWeight.ContainsKey(singleRelation.ProductId))
        //            productsWeight[singleRelation.ProductId]++;
        //        else
        //            productsWeight.Add(singleRelation.ProductId, 1);
        //    }

        //    // Get only products which fulfill current conditions count
        //    productsWeight = productsWeight.Where(i => i.Value >= conditionsEnumerable.Count())
        //        .ToDictionary(i => i.Key, i => i.Value);

        //    // Order by most common??
        //    //productsWeight = productsWeight.OrderByDescending(x => x.Value).ToDictionary(x => x.Key, x => x.Value);

        //    var matchedProductsIds = new List<int>(productsWeight.Keys);

        //    return await ExpertContext.Products.Where(product => matchedProductsIds.Contains(product.Id))
        //        .ToListAsync();
        //}

        /// <summary>
        /// Looks for the most common condition in the expert system.
        /// </summary>
        /// <returns>ID of the most common condition in the array, if it exists.</returns>
        private async Task<SolverResponse> GenerateFirstResponse()
        {
            // Get all system conditions and group them by most popular
            var mostCommonConditions = await ExpertContext.Relations
                .Where(relation => relation.SystemId == _question.SystemId)
                .GroupBy(q => q.CondiotionId)
                .OrderByDescending(gp => gp.Count())
                .Select(g => g.Key) // Key of the group, i.e. the key by which it is grouped
                .ToArrayAsync();

            var productsCount = await ExpertContext.Products.Where(prod => prod.SystemId == _question.SystemId)
                .CountAsync();
            var nextConditionId = 0;

            // If all Products are related to the most common condition, skip it.
            foreach (var singleConditionId in mostCommonConditions)
            {
                if (await ExpertContext.Relations.Where(rel => rel.CondiotionId == singleConditionId).CountAsync() >=
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
            if (status == SolverStatus.Unknown && nextConditions.Any()) status = SolverStatus.NewQuestion;

            if (status == SolverStatus.Unknown && resultingProducts.Any()) status = SolverStatus.Solved;

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
}