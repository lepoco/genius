// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.ComponentModel.DataAnnotations;

namespace Genius.Data.Models.Expert
{
    /// <summary>
    /// Represents an expert system.
    /// </summary>
    public class System
    {
        public int Id { get; set; }

        [Required]
        public string Version { get; set; } = "1.0.0";

        [Required]
        public string Name { get; set; } = "";

        [Required]
        public string Description { get; set; } = "";

        [Required]
        public string Guid { get; set; } = "";

        [Required]
        public string Question { get; set; } = "";

        [Required]
        public SystemType Type { get; set; } = SystemType.Conditional;

        public DateTime CreatedAt { get; set; } = DateTime.Now;

        public DateTime UpdatedAt { get; set; } = DateTime.Now;
    }
}
