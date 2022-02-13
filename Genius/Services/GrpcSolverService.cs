// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Expert.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Genius.Services
{
    public class GrpcSolverService : Solver.SolverBase
    {
        private readonly ILogger<GrpcSolverService> _logger;

        private readonly IExpertService _genius;

        public GrpcSolverService(ILogger<GrpcSolverService> logger, IExpertService genius)
        {
            _logger = logger;
            _genius = genius;
        }

        public override async Task<SolverResponse> Ask(SolverQuestion request, ServerCallContext context)
        {
            var response = await _genius.Solver.Solve(BuildQuestion(request));

            int nextCondition = 0;

            if (response.NextConditions != null && response.NextConditions.Any())
                nextCondition = response.NextConditions.First().Id;

            IEnumerable<int> productIds = new int[] { };

            if (response.ResultingProducts.Any())
                productIds = response.ResultingProducts.Select(prod => prod.Id).ToArray();

            return new SolverResponse
            {
                SystemId = response.SystemId,
                IsSolved = response.IsSolved,
                Multiple = response.IsMultiple,
                NextCondition = nextCondition,
                Status = (int)response.Status,
                Products = { productIds }
            };
        }

        private ISolverQuestion BuildQuestion(SolverQuestion grpcQuestion)
        {
            var internalQuestion = new Expert.SolverQuestion
            {
                IsMultiple = grpcQuestion?.Multiple ?? true,
                SystemId = grpcQuestion?.SystemId ?? 0
            };

            internalQuestion.Confirming = grpcQuestion?.Confirming.ToArray();
            internalQuestion.Negating = grpcQuestion?.Negating.ToArray();
            internalQuestion.Indifferent = grpcQuestion?.Indifferent.ToArray();

            //internalQuestion.Confirming = await _genius.ExpertContext.Conditions.Where(con => confirmingConditionsIds.Contains(con.Id)).ToListAsync();
            //internalQuestion.Negating = await _genius.ExpertContext.Conditions.Where(con => negatingConditionsIds.Contains(con.Id)).ToListAsync();
            //internalQuestion.Indifferent = await _genius.ExpertContext.Conditions.Where(con => indifferentConditionsIds.Contains(con.Id)).ToListAsync();

            return internalQuestion;
        }
    }
}