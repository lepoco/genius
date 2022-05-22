// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Threading.Tasks;
using Genius.Data.Contexts;

namespace Genius.Expert.Interfaces;

/// <summary>
/// Solver is responsible for finding the next <see cref="Data.Models.Expert.Condition"/> or <see cref="Data.Models.Expert.Product"/>.
/// </summary>
public interface ISolver
{
    /// <summary>
    /// Defines the database context.
    /// </summary>
    /// <param name="context">Database context.</param>
    public void SetContext(IExpertContext context);

    /// <summary>
    /// Tries to solve the expert system, finding the next condition for the question or returning products that match.
    /// </summary>
    /// <param name="question">Local question instance to solver.</param>
    /// <returns>Solver solution as <see cref="ISolverResponse"/>.</returns>
    public Task<ISolverResponse> Solve(ISolverQuestion question);
}
