// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Threading.Tasks;
using Genius.Data.Contexts;
using Genius.Expert.Interfaces;

namespace Genius.Expert;

/// <summary>
/// Solver is responsible for finding the next <see cref="Data.Models.Expert.Condition"/> or <see cref="Data.Models.Expert.Product"/>.
/// <para>This abstract implementation defines the underlying body methods that repeat themselves.</para>
/// </summary>
public abstract class SolverBase : ISolver
{
    /// <summary>
    /// Contains the database context for expert systems.
    /// </summary>
    protected IExpertContext ExpertContext;

    public void SetContext(IExpertContext context)
    {
        ExpertContext = context;
    }

    public abstract Task<ISolverResponse> Solve(ISolverQuestion question);
}
