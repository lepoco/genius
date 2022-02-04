using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using System.Collections.Generic;

namespace Genius.Expert
{
    public class SolverQuestion : ISolverQuestion
    {
        public int SystemId { get; set; } = 0;

        public bool IsMultiple { get; set; } = true;

        public IEnumerable<Condition> Confirming { get; set; } = new List<Condition>();

        public IEnumerable<Condition> Negating { get; set; } = new List<Condition>();

        public IEnumerable<Condition> Indifferent { get; set; } = new List<Condition>();
    }
}