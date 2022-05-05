// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Expert.Interfaces;

namespace Genius.Expert
{
    /// <summary>
    /// Represents the <see cref="ISolver"/> response.
    /// </summary>
    public class SolverResponse : ISolverResponse
    {
        /// <inheritdoc />
        public int SystemId { get; set; } = 0;

        /// <inheritdoc />
        public bool IsMultiple { get; set; } = false;

        /// <inheritdoc />
        public bool IsSolved { get; set; } = false;

        /// <inheritdoc />
        public SolverStatus Status { get; set; } = SolverStatus.Unknown;

        /// <inheritdoc />
        public int[] NextConditions { get; set; } = { };

        /// <inheritdoc />
        public int[] ResultingProducts { get; set; } = { };
    }
}
