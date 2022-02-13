// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using Genius.Data.Models.Expert;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Genius.Expert.Interfaces
{
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

        /// <summary>
        /// Finds the next condition based on the previous conditions. If it doesn't find a new one, we should return the products.
        /// </summary>
        /// <param name="systemId">The system in which the <see cref="Condition"/> is to be found.</param>
        /// <param name="confirmingConditions">Confirming conditions represent logical <see langword="AND"/>.</param>
        /// <param name="negatingConditions">Negating conditions represent logical <see langword="NOT"/>.</param>
        /// <param name="indifferentConditions">Indifferent conditions represent logical <see langword="OR"/>.</param>
        public Task<IEnumerable<Condition>> FindNextCondition(
            int systemId,
            IEnumerable<int> confirmingConditions,
            IEnumerable<int> negatingConditions,
            IEnumerable<int> indifferentConditions);
    }
}
