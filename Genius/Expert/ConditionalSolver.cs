// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using Microsoft.EntityFrameworkCore;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Genius.Expert
{
    /// <summary>
    /// Solves questions on the basis of the provided <see cref="Condition"/>'s by searching for a suitable <see cref="Product"/> or by returning the next <see cref="Condition"/> to ask.
    /// </summary>
    public class ConditionalSolver : ISolver
    {
        private IExpertContext _expertContext;

        /// <summary>
        /// Defines the database context.
        /// </summary>
        /// <param name="context">Database context.</param>
        public void SetContext(IExpertContext context)
        {
            _expertContext = context;
        }

        /// <summary>
        /// Tries to solve the expert system, finding the next condition for the question or returning products that match.
        /// </summary>
        /// <param name="question">Local question instance to solver.</param>
        /// <returns>Solver solution as <see cref="ISolverResponse"/>.</returns>
        public async Task<ISolverResponse> Solve(ISolverQuestion question)
        {
            var response = new SolverResponse
            {
                SystemId = question.SystemId,
                IsMultiple = question.IsMultiple,
                Status = SolverStatus.Unknown
            };

            if (response.SystemId < 1)
                return response;

            if (!question.Confirming.Any() && !question.Negating.Any() && !question.Indifferent.Any())
            {
                response.NextConditions = await GetFirstCondition(response.SystemId);
                response.Status = SolverStatus.NewQuestion;

                return response;
            }

            var nextConditions = await FindNextCondition(response.SystemId, question.Confirming, question.Negating,
                question.Indifferent);

            var nextConditionsEnumerable = nextConditions as Condition[] ?? nextConditions.ToArray();
            response.NextConditions = nextConditionsEnumerable;

            // TODO: This is a specific solution, because we have to make sure that not returning any new condition results in at least one product.
            if (!nextConditionsEnumerable.Any())
            {
                response.ResultingProducts = await FindProducts(
                    response.SystemId,
                    question.Confirming,
                    question.Negating,
                    question.Indifferent);

                response.Status = SolverStatus.Solved;
            }

            return response;
        }

        /// <summary>
        /// Finds the next condition based on the previous conditions. If it doesn't find a new one, we should return the products.
        /// </summary>
        /// <param name="systemId">The system in which the <see cref="Condition"/> is to be found.</param>
        /// <param name="confirmingConditions">Confirming conditions represent logical <see langword="AND"/>.</param>
        /// <param name="negatingConditions">Negating conditions represent logical <see langword="NOT"/>.</param>
        /// <param name="indifferentConditions">Indifferent conditions represent logical <see langword="OR"/>.</param>
        /// <returns></returns>
        public async Task<IEnumerable<Condition>> FindNextCondition(
            int systemId,
            IEnumerable<int> confirmingConditions,
            IEnumerable<int> negatingConditions,
            IEnumerable<int> indifferentConditions)
        {
            var conditions = new List<Condition>();

            var matchingProducts =
                await GetMatchingProducts(systemId, confirmingConditions, negatingConditions, indifferentConditions);

            // If only one product matches the current pool, don't return anything because that's the result.
            var productsEnumerable = matchingProducts.ToList();
            if (productsEnumerable?.Count() < 1)
                return conditions; // find which condition changes the most

            var matchedProductsIds = productsEnumerable.Select(product => product.Id).ToList();

            var availableConditions = await _expertContext.Relations
                .Where(relation => relation.SystemId == systemId && matchedProductsIds.Contains(relation.ProductId) &&
                                   !confirmingConditions.Contains(relation.CondiotionId))
                .GroupBy(relation => relation.CondiotionId)
                .Select(g => g.Key)
                .ToListAsync();

            if (!availableConditions.Any())
                return conditions;

            var nextId = availableConditions.First();

            if (nextId < 1)
                return conditions;

            var nextCondition =
                await _expertContext.Conditions.Where(condition => condition.Id == nextId).FirstAsync();

            if (nextCondition.Id > 0)
                conditions.Add(nextCondition);

            return conditions;
        }

        /// <summary>
        /// Finds products that meet expectations.
        /// </summary>
        /// <param name="systemId">The system in which the <see cref="Product"/> is to be found.</param>
        /// <param name="multiple">Decides whether we can have multiple results.</param>
        /// <param name="confirmingConditions">Confirming conditions represent logical <see langword="AND"/>.</param>
        /// <param name="negatingConditions">Negating conditions represent logical <see langword="NOT"/>.</param>
        /// <param name="indifferentConditions">Indifferent conditions represent logical <see langword="OR"/>.</param>
        /// <returns></returns>
        public async Task<IEnumerable<Product>> FindProducts(
            int systemId,
            IEnumerable<int> confirmingConditions,
            IEnumerable<int> negatingConditions,
            IEnumerable<int> indifferentConditions)
        {
            return await GetMatchingProducts(systemId, confirmingConditions, negatingConditions, indifferentConditions);
        }

        // Good Old Fashioned AI
        // Symbolic AI - symbols, logic
        // Symbols are nouns, and Relations are adjectives or verbs
        // Knowledge base - contains all symbols and relations

        // Symbols - car, man
        // Logical connectives - AND OR
        // Relations - Drives, Old
        // Propositions logic
        // Truth table

        // Inference - finding new propositions based on knowledge base

        /// <summary>
        /// It takes the first (most popular), <see cref="Condition"/> for a given <see cref="Data.Models.Expert.System"/>.
        /// </summary>
        /// <param name="systemId">The system in which the <see cref="Condition"/> is to be found.</param>
        /// <returns>List of next <see cref="Condition"/>'s to query.</returns>
        public async Task<IEnumerable<Condition>> GetFirstCondition(int systemId)
        {
            var conditions = new List<Condition>();

            var mostCommonRelations = await _expertContext.Relations.Where(relation => relation.SystemId == systemId)
                .GroupBy(q => q.CondiotionId)
                .OrderByDescending(gp => gp.Count())
                .Take(1)
                .Select(g => g.Key).ToListAsync();

            if (!mostCommonRelations.Any())
                return conditions;

            var conditionId = mostCommonRelations.First();

            var mostCommonCondition = await _expertContext.Conditions.Where(con => con.Id == conditionId)
                .FirstAsync();

            if (mostCommonCondition?.Id < 1)
                return conditions;

            conditions.Add(mostCommonCondition);

            return conditions;
        }

        private async Task<IEnumerable<Product>> GetMatchingProducts(
            int systemId,
            IEnumerable<int> confirmingConditions,
            IEnumerable<int> negatingConditions,
            IEnumerable<int> indifferentConditions)
        {
            List<Relation> matchedRelations;

            // TODO: This array can be pretty huge
            // Select from database all relations which don't contradict
            matchedRelations = await _expertContext.Relations
                .Where(relation => relation.SystemId == systemId && !negatingConditions.Contains(relation.CondiotionId))
                .ToListAsync();

            // If there's confirming, get only them
            var conditionsEnumerable = confirmingConditions as int[] ?? confirmingConditions.ToArray();

            if (conditionsEnumerable.Any())
                matchedRelations = matchedRelations
                    .Where(relation => conditionsEnumerable.Contains(relation.CondiotionId)).ToList();

            // Create matrix of products with its conditions count
            var productsWeight = new Dictionary<int, int>();

            foreach (var singleRelation in matchedRelations)
            {
                if (productsWeight.ContainsKey(singleRelation.ProductId))
                    productsWeight[singleRelation.ProductId]++;
                else
                    productsWeight.Add(singleRelation.ProductId, 1);
            }

            // Get only products which fulfill current conditions count
            productsWeight = productsWeight.Where(i => i.Value >= conditionsEnumerable.Count())
                .ToDictionary(i => i.Key, i => i.Value);

            // Order by most common??
            //productsWeight = productsWeight.OrderByDescending(x => x.Value).ToDictionary(x => x.Key, x => x.Value);

            var matchedProductsIds = new List<int>(productsWeight.Keys);

            return await _expertContext.Products.Where(product => matchedProductsIds.Contains(product.Id))
                .ToListAsync();
        }
    }
}