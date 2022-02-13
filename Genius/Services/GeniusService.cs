// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Contexts;
using Genius.Expert;
using Genius.Expert.Interfaces;

namespace Genius.Services
{
    public class GeniusService : IExpertService
    {
        public IExpertContext ExpertContext { get; }

        public ISolver Solver { get; }

        public GeniusService(IExpertContext expertContext)
        {
            ExpertContext = expertContext;

            // TODO: Set different solver
            Solver = new ConditionalSolver();
            Solver.SetContext(expertContext);
        }
    }
}
