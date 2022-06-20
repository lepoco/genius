// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Threading.Tasks;
using Genius.Core.Data.Models.Expert;
using Genius.Core.Expert.Interfaces;

namespace Genius.Core.Expert.Solvers;

/// <summary>
/// Solves questions on the basis of the provided <see cref="Condition"/>'s by searching for a suitable <see cref="Product"/> or by returning the next <see cref="Condition"/> to ask.
/// <para>It returns quick results without much confidence, based on <see cref="Genius.Core.Data.Models.Expert.System.Confidence"/></para>
/// </summary>
public class ConditionalNotConfidentSolver : SolverBase
{
    /// <inheritdoc />
    public override async Task<ISolverResponse> Solve(ISolverQuestion solverQuestion)
    {
        throw new NotImplementedException();

        //AskedQuestion = solverQuestion;
        //return GenerateEmptyResponse();
    }
}

