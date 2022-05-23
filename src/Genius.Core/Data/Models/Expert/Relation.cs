// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Genius.Core.Data.Models.Expert;

/// <summary>
/// Represents expert system relation in the database.
/// </summary>
public class Relation
{
    /// <summary>
    /// Unique relation identifier.
    /// </summary>
    [Key]
    public int Id { get; set; }

    /// <summary>
    /// Id of the <see cref="System"/>.
    /// </summary>
    [Required]
    [ForeignKey("System")]
    [ConcurrencyCheck]
    public int SystemId { get; set; }

    /// <summary>
    /// Id of the <see cref="Condition"/>.
    /// </summary>
    [Required]
    [ForeignKey("Condition")]
    [ConcurrencyCheck]
    public int ConditionId { get; set; }

    /// <summary>
    /// Id of the <see cref="Product"/>.
    /// </summary>
    [Required]
    [ForeignKey("Product")]
    [ConcurrencyCheck]
    public int ProductId { get; set; }

    /// <summary>
    /// Fuzzy weight of the relation.
    /// </summary>
    [Required]
    [Range(0, 100)]
    public int Weight { get; set; } = 100;

    /// <summary>
    /// Whether or not the required relation is not met is contradictory for the product.
    /// </summary>
    [Required]
    public bool Exclusive { get; set; } = true;

    /// <summary>
    /// Type of the relation.
    /// </summary>
    [Required]
    public RelationType Type { get; set; } = RelationType.Compliance;

    /// <summary>
    /// Timestamp used for concurrency validation.
    /// </summary>
    [Timestamp]
    public byte[] Timestamp { get; set; }
}
