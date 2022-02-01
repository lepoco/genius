// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Interfaces;
using GeniusProtocol;
using Microsoft.Extensions.Logging;
using System.Threading.Tasks;

namespace Genius.Client.Services
{
    public class GrpcSolverClientService
    {
        private readonly ILogger<GrpcSolverClientService> _logger;

        private readonly Solver.SolverClient _grpcClient;

        public GrpcSolverClientService(ILogger<GrpcSolverClientService> logger, IChannel channel)
        {
            _logger = logger;
            _grpcClient = new Solver.SolverClient(channel.GetChannel());
        }

        /// <summary>
        /// Wraps <see cref="Solver.SolverClient.AskAsync"/>.
        /// </summary>
        public async Task<SolverResponse> AskAsync(SolverQuestion question)
        {
            return await _grpcClient.AskAsync(question);
        }
    }
}