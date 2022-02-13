// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using System.Collections.Generic;

namespace Genius.Expert.Interfaces
{
    public interface ISolverResponse
    {
        public int SystemId { get; set; }

        public bool IsMultiple { get; set; }

        public bool IsSolved { get; set; }

        public SolverStatus Status { get; set; }

        public IEnumerable<Condition> NextConditions { get; set; }

        public IEnumerable<Product> ResultingProducts { get; set; }

    }
}
