// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.ComponentModel.DataAnnotations;

namespace Genius.Data.Models.Expert;

/// <summary>
/// Represents an expert system.
/// </summary>
public class System
{
    /// <summary>
    /// Unique system identifier.
    /// </summary>
    public int Id { get; set; }

    /// <summary>
    /// Version of the Genius service, at which the system was created.
    /// </summary>
    [Required]
    public string Version { get; set; } = "1.0.0";

    /// <summary>
    /// Name of the expert system.
    /// </summary>
    [Required]
    public string Name { get; set; } = "";

    /// <summary>
    /// Description of the expert system.
    /// </summary>
    [Required]
    public string Description { get; set; } = "";

    /// <summary>
    /// Unique GUID of the system.
    /// </summary>
    [Required]
    public string Guid { get; set; } = "";

    /// <summary>
    /// Question to ask by system.
    /// </summary>
    [Required]
    public string Question { get; set; } = "";

    /// <summary>
    /// Type of the expert system, based on which the solver is choosen.
    /// </summary>
    [Required]
    public SystemType Type { get; set; } = SystemType.Conditional;

    /// <summary>
    /// Date of creation.
    /// </summary>
    public DateTime CreatedAt { get; set; } = DateTime.Now;

    /// <summary>
    /// Date of update.
    /// </summary>
    public DateTime UpdatedAt { get; set; } = DateTime.Now;
}
