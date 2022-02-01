// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using System.Threading.Tasks;

namespace Genius.Services
{
    public class GrpcSolverService : GeniusProtocol.Solver.SolverBase
    {
        private readonly ILogger<GrpcSolverService> _logger;

        private readonly ExpertContext _expertContext;

        public GrpcSolverService(ILogger<GrpcSolverService> logger, ExpertContext expertContext)
        {
            _logger = logger;
            _expertContext = expertContext;
        }

        public override async Task<SolverResponse> Ask(SolverQuestion request, ServerCallContext context)
        {
            // TODO:
            return new SolverResponse
            {
                IsSolved = false
            };
        }
    }
}