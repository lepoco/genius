// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

namespace Genius.Core.Data.Models.Expert;

/// <summary>
/// Available types of relations.
/// </summary>
public enum RelationType
{
    /// <summary>
    /// The condition confirms the product.
    /// </summary>
    Compliance,

    /// <summary>
    /// The condition negates the product.
    /// </summary>
    Contradiction,

    /// <summary>
    /// The condition is inert to the product.
    /// </summary>
    Disregard
}

