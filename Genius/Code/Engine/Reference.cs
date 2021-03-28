// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

namespace Genius.Code.Engine
{
    /// <summary>
    /// A reference to a <see cref="Engine.Condition"/> for <see cref="Engine.Product"/>, which contains information about whether or not it belongs to it.
    /// </summary>
    public sealed class Reference : Parents.ExpertReference
    {
        /// <summary>
        /// Gets or sets reference to <see cref="Engine.Product"/> stored in <see cref="Engine.KnowledgeBase"/>.
        /// </summary>
        public int ProductId { get; set; }

        /// <summary>
        /// Gets or sets reference to <see cref="Engine.Condition"/> stored in <see cref="Engine.KnowledgeBase"/>.
        /// </summary>
        public int ConditionId { get; set; }

        /// <summary>
        /// Gets or sets <see cref="Engine.Condition"/> ID's that are excluded if this one is met.
        /// </summary>
        public int[] Exclusions { get; set; }

        /// <summary>
        /// Gets or sets <see cref="Engine.Condition"/> ID's that must come along with this one.
        /// </summary>
        public int[] Requirements { get; set; }
        
        /// <summary>
        /// Gets or sets belonging to the result. In the case of using the traditional inference method, this condition determines whether a given <see cref="Engine.Condition"/> meets the requirements of belonging to <see cref="Engine.Product"/>.
        /// </summary>
        public bool? Fulfil { get; set; }

        /// <summary>
        /// Gets or sets the <see cref="Engine.Condition"/> (<see cref="Engine.Reference"/>) weight, which is needed while the predictive engine uses fuzzy sets.
        /// <para>The weight, depending on the configuration, determines how much the element meets or does not meet the belonging to the <see cref="Engine.Product"/>, or whether it is indifferent to it.</para>
        /// </summary>
        public int Weight { get; set; }
    }
}
