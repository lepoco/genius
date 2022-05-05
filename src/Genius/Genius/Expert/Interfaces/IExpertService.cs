// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using System.Threading.Tasks;

namespace Genius.Expert.Interfaces
{
    /// <summary>
    /// Stores the solver status and databases in a given scope.
    /// </summary>
    public interface IExpertService
    {
        /// <summary>
        /// Points to the database ORM.
        /// </summary>
        public IExpertContext Context { get; }

        /// <summary>
        /// Takes an instance of the specified solver.
        /// </summary>
        /// <typeparam name="T">Specific solver implementation</typeparam>
        /// <returns></returns>
        public T GetSolver<T>();

        /// <summary>
        /// Asks a question to the solver of type T.
        /// </summary>
        /// <typeparam name="T">Specified custom solver.</typeparam>
        /// <param name="question">Instance of solver question.</param>
        /// <returns>Resolved question.</returns>
        public Task<ISolverResponse> Solve<T>(ISolverQuestion question);
    }
}
