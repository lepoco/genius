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
    public class Solver
    {
        private ExpertContext _expertContext;

        public void SetContext(ExpertContext context)
        {
            _expertContext = context;
        }

        public async Task<ISolverResponse> Solve(ISolverQuestion question)
        {
            var response = new SolverResponse
            {
                SystemId = question.SystemId,
                IsMultiple = question.IsMultiple
            };

            if (response.SystemId < 1)
                return response;

            if (!question.Confirming.Any() && !question.Negating.Any() && !question.Indifferent.Any())
            {
                response.NextConditions = await GetFirstCondition(response.SystemId);

                return response;
            }


            return response;
        }

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
    }
}
