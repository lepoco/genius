// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Genius.Core.Data.Models.Expert;

/// <summary>
/// Represents expert system product in the database.
/// </summary>
public class Product
{
    /// <summary>
    /// Unique product identifier.
    /// </summary>
    [Key]
    public int Id { get; set; }

    /// <summary>
    /// Id of the <see cref="System"/>.
    /// </summary>
    [Required]
    [ForeignKey("System")]
    public int SystemId { get; set; }

    /// <summary>
    /// Product unique name.
    /// </summary>
    [Required]
    [ConcurrencyCheck]
    public string Name { get; set; }

    /// <summary>
    /// Product description.
    /// </summary>
    public string Description { get; set; } = String.Empty;

    /// <summary>
    /// Product notes.
    /// </summary>
    public string Notes { get; set; } = String.Empty;

    /// <summary>
    /// Timestamp used for concurrency validation.
    /// </summary>
    [Timestamp]
    public byte[] Timestamp { get; set; }
}
