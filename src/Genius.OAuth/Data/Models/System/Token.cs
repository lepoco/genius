// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Genius.OAuth.Data.Models.System;

public class Token
{
    [Key]
    public int Id { get; set; }

    [Required]
    [ForeignKey("User")]
    public string UserId { get; set; }

    [Required]
    [ConcurrencyCheck]
    public string PublicToken { get; set; }

    [Required]
    [ConcurrencyCheck]
    public string PrivateToken { get; set; }

    [Required]
    public DateTime CreatedAt { get; set; } = DateTime.Now;

    /// <summary>
    /// Timestamp used for concurrency validation.
    /// </summary>
    [Timestamp]
    public byte[] Timestamp { get; set; }
}
