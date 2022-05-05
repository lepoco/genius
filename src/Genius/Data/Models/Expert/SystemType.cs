// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Data.Models.Expert
{
    /// <summary>
    /// Represents a method of solving a question in a given expert system.
    /// </summary>
    public enum SystemType
    {
        /// <summary>
        /// The easiest solution, based on AND, OR, OR logic.
        /// </summary>
        Conditional,

        /// <summary>
        /// Each relationship meets the condition to some extent, on a scale from 0 to 100.
        /// </summary>
        FuzzyScore,

        /// <summary>
        /// Relationships are dependent on other relationships as well as belonging on a scale of 0 to 100
        /// </summary>
        FuzzyMultiValue
    }
}
