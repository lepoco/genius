// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Genius.Data.Models.Expert
{
    public class Relation
    {
        public int Id { get; set; }

        [Required]
        [ForeignKey("System")]
        public int SystemId { get; set; }

        [Required]
        [ForeignKey("Condition")]
        public int CondiotionId { get; set; }

        [Required]
        [ForeignKey("Product")]
        public int ProductId { get; set; }

        [Required]
        [Range(0, 100)]
        public int Weight { get; set; }

        [Required] public RelationType Type { get; set; } = RelationType.Compliance;
    }
}
