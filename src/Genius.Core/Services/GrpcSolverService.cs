// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Linq;
using System.Threading.Tasks;
using Genius.Core.Expert.Interfaces;
using Genius.Core.Expert.Solvers;
using Genius.Protocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using SolverQuestion = Genius.Protocol.SolverQuestion;
using SolverResponse = Genius.Protocol.SolverResponse;

namespace Genius.Core.Services;

public class GrpcSolverService : Genius.Protocol.Solver.SolverBase
{
    private readonly ILogger<GrpcSolverService> _logger;

    private readonly IExpertService _genius;

    public GrpcSolverService(ILogger<GrpcSolverService> logger, IExpertService genius)
    {
        _logger = logger;
        _genius = genius;
    }

    public override async Task<SolverResponse> Ask(SolverQuestion solverQuestion, ServerCallContext context)
    {
        ISolverResponse response;

        switch (solverQuestion.Type)
        {
            case SolverType.ConditionalStrict:
                response = await _genius.Solve<StrictConditionalSolver>(new Expert.SolverQuestion
                {
                    IsMultiple = solverQuestion?.Multiple ?? true,
                    SystemId = solverQuestion?.SystemId ?? 0,
                    Confirming = solverQuestion?.Confirming?.ToArray() ?? new int[] { },
                    Negating = solverQuestion?.Negating?.ToArray() ?? new int[] { },
                    Indifferent = solverQuestion?.Indifferent?.ToArray() ?? new int[] { }
                });
                break;

            case SolverType.ConditionalNotConfident:
                response = await _genius.Solve<ConditionalNotConfidentSolver>(new Expert.SolverQuestion
                {
                    IsMultiple = solverQuestion?.Multiple ?? true,
                    SystemId = solverQuestion?.SystemId ?? 0,
                    Confirming = solverQuestion?.Confirming?.ToArray() ?? new int[] { },
                    Negating = solverQuestion?.Negating?.ToArray() ?? new int[] { },
                    Indifferent = solverQuestion?.Indifferent?.ToArray() ?? new int[] { }
                });
                break;

            case SolverType.Fuzzy:
                response = await _genius.Solve<FuzzySolver>(new Expert.SolverQuestion
                {
                    IsMultiple = solverQuestion?.Multiple ?? true,
                    SystemId = solverQuestion?.SystemId ?? 0,
                    Confirming = solverQuestion?.Confirming?.ToArray() ?? new int[] { },
                    Negating = solverQuestion?.Negating?.ToArray() ?? new int[] { },
                    Indifferent = solverQuestion?.Indifferent?.ToArray() ?? new int[] { }
                });
                break;

            default: // Conditional
                response = await _genius.Solve<ConditionalSolver>(new Expert.SolverQuestion
                {
                    IsMultiple = solverQuestion?.Multiple ?? true,
                    SystemId = solverQuestion?.SystemId ?? 0,
                    Confirming = solverQuestion?.Confirming?.ToArray() ?? new int[] { },
                    Negating = solverQuestion?.Negating?.ToArray() ?? new int[] { },
                    Indifferent = solverQuestion?.Indifferent?.ToArray() ?? new int[] { }
                });
                break;
        }

        return new SolverResponse
        {
            SystemId = response.SystemId,
            IsSolved = response.IsSolved,
            Multiple = response.IsMultiple,
            Status = (int)response.Status,
            NextCondition = response.NextConditions.FirstOrDefault(0),
            Products = { response.ResultingProducts }
        };
    }
}
