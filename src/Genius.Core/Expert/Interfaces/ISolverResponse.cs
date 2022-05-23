// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Core.Expert.Interfaces;

/// <summary>
/// Represents the <see cref="ISolver"/> response.
/// </summary>
public interface ISolverResponse
{
    /// <summary>
    /// Identifier of the expert system to which the question is asked.
    /// </summary>
    public int SystemId { get; set; }

    /// <summary>
    /// Is multiple results possible.
    /// </summary>
    public bool IsMultiple { get; set; }

    /// <summary>
    /// Whether the system was resolved.
    /// </summary>
    public bool IsSolved { get; set; }

    /// <summary>
    /// Current response status.
    /// </summary>
    public SolverStatus Status { get; set; }

    /// <summary>
    /// The IDs of the <see cref="Data.Models.Expert.Condition"/> to be queried next.
    /// </summary>
    public int[] NextConditions { get; set; }

    /// <summary>
    /// <see cref="Data.Models.Expert.Product"/> IDs that are <see cref="ISolver"/> results.
    /// </summary>
    public int[] ResultingProducts { get; set; }

}
