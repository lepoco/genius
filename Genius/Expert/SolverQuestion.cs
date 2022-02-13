// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Expert.Interfaces;
using System.Collections.Generic;

namespace Genius.Expert
{
    public class SolverQuestion : ISolverQuestion
    {
        public int SystemId { get; set; } = 0;

        public bool IsMultiple { get; set; } = true;

        public IEnumerable<int> Confirming { get; set; } = new List<int>();

        public IEnumerable<int> Negating { get; set; } = new List<int>();

        public IEnumerable<int> Indifferent { get; set; } = new List<int>();
    }
}