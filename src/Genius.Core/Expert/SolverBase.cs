// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Linq;
using System.Threading.Tasks;
using Genius.Core.Data.Contexts;
using Genius.Core.Expert.Interfaces;

namespace Genius.Core.Expert;

/// <summary>
/// Solver is responsible for finding the next <see cref="Data.Models.Expert.Condition"/> or <see cref="Data.Models.Expert.Product"/>.
/// <para>This abstract implementation defines the underlying body methods that repeat themselves.</para>
/// </summary>
public abstract class SolverBase : ISolver
{
    protected ISolverQuestion AskedQuestion;

    /// <summary>
    /// Contains the database context for expert systems.
    /// </summary>
    protected IExpertContext ExpertContext;

    public void SetContext(IExpertContext context)
    {
        ExpertContext = context;
    }

    public abstract Task<ISolverResponse> Solve(ISolverQuestion question);

    /// <summary>
    /// Generates an empty answer, with no conditions or products.
    /// </summary>
    protected virtual SolverResponse GenerateEmptyResponse()
    {
        return GenerateResponse(new int[] { }, new int[] { });
    }

    /// <summary>
    /// Generates a new response based on the parameters provided.
    /// </summary>
    protected SolverResponse GenerateResponse(int[] nextConditions, int[] resultingProducts,
        SolverStatus status = SolverStatus.Unknown)
    {
        if (status == SolverStatus.Unknown && nextConditions.Any())
            status = SolverStatus.NewQuestion;

        if (status == SolverStatus.Unknown && resultingProducts.Any())
            status = SolverStatus.Solved;

        return new SolverResponse
        {
            SystemId = AskedQuestion.SystemId,
            IsMultiple = AskedQuestion.IsMultiple,
            Status = status,
            IsSolved = resultingProducts.Any(),
            ResultingProducts = resultingProducts,
            NextConditions = nextConditions
        };
    }
}
