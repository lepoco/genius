// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Core.Expert.Interfaces;

/// <summary>
/// Represents the <see cref="ISolver"/> question.
/// </summary>
public interface ISolverQuestion
{
    /// <summary>
    /// The system to which the question is asked.
    /// </summary>
    public int SystemId { get; set; }

    /// <summary>
    /// Is multiple results possible.
    /// </summary>
    public bool IsMultiple { get; set; }

    /// <summary>
    /// <see cref="Data.Models.Expert.Condition"/>'s that confirm the <see cref="Data.Models.Expert.Product"/> you are looking for.
    /// </summary>
    public int[] Confirming { get; set; }

    /// <summary>
    /// <see cref="Data.Models.Expert.Condition"/>'s that negate the <see cref="Data.Models.Expert.Product"/> you are looking for.
    /// </summary>
    public int[] Negating { get; set; }

    /// <summary>
    /// <see cref="Data.Models.Expert.Condition"/>'s that are irrelevant to the <see cref="Data.Models.Expert.Product"/> you are looking for.
    /// </summary>
    public int[] Indifferent { get; set; }

    /// <summary>
    /// Indicates whether all the question <see cref="Data.Models.Expert.Condition"/>'s are empty.
    /// </summary>
    public bool IsEmpty();
}
