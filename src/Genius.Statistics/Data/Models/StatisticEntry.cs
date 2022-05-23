// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.ComponentModel.DataAnnotations;

namespace Genius.Statistics.Data.Models;

/// <summary>
/// Represents statistic entry in the database.
/// </summary>
public class StatisticEntry
{
    /// <summary>
    /// Unique id.
    /// </summary>
    [Key]
    [ConcurrencyCheck]
    public int Id { get; set; }

    /// <summary>
    /// Statistic type.
    /// </summary>
    [Required]
    public StatisticType Type { get; set; }

    /// <summary>
    /// Context of the entry.
    /// </summary>
    [Required]
    public string Context { get; set; }

    /// <summary>
    /// Date of creation.
    /// </summary>
    public DateTime CreatedAt { get; set; } = DateTime.Now;

    /// <summary>
    /// Timestamp used for concurrency validation.
    /// </summary>
    [Timestamp]
    public byte[] Timestamp { get; set; }
}
