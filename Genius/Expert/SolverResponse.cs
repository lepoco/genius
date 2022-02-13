// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using System.Collections.Generic;

namespace Genius.Expert
{
    public class SolverResponse : ISolverResponse
    {
        public int SystemId { get; set; } = 0;

        public bool IsMultiple { get; set; } = false;

        public bool IsSolved { get; set; } = false;

        public SolverStatus Status { get; set; } = SolverStatus.Unknown;

        public IEnumerable<Condition> NextConditions { get; set; } = new List<Condition>();

        public IEnumerable<Product> ResultingProducts { get; set; } = new List<Product>();
    }
}
