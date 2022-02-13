﻿// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Collections.Generic;

namespace Genius.Expert.Interfaces
{
    public interface ISolverQuestion
    {
        public int SystemId { get; set; }

        public bool IsMultiple { get; set; }

        public IEnumerable<int> Confirming { get; set; }

        public IEnumerable<int> Negating { get; set; }

        public IEnumerable<int> Indifferent { get; set; }
    }
}